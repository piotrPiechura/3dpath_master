<?php
class CorePaginationInitialChar implements iCorePagination {
	protected $controller = controller;
	protected $address = null;
	protected $initialChars = null;

	protected $currentChar = null;

 	public function __construct($controller, $address) {
		$this->controller = $controller;
		$this->address = CoreServices::get('url')->stripParams($address, array('page'));
		$this->init();
	}

	public function getType() {
		return 'InitialChar';
	}

	public function getPageCount() {
		return sizeof($this->initialChars);
	}

	public function getCurrentChar() {
		return $this->currentChar;
	}

	public function getLinksHTML() {
		$url = CoreServices::get('url');
		$result = array();
		foreach ($this->initialChars as $character) {
			$result[$character] = $url->appendHTML($this->address, 'page', $character);
		}
		return $result;
	}

	public static function getInitialChar($string, $lang = null) {
		if (is_null($lang)) {
			$lang = 'universal';
		}
		if (!array_key_exists($lang, CoreConfigDisplay::$paginationChars)) {
			throw new CoreException('Invalid language \'' . $lang . '\' for first character pagination!');
		}
		if (empty($string)) {
			return CoreConfigDisplay::paginationDummy;
		}
		$firstChar = mb_strtoupper(CoreUtils::substr($string, 0, 1), CoreConfigDisplay::globalCharset);
		foreach (CoreConfigDisplay::$paginationChars[$lang] as $shownChar => $initials) {
			if (in_array($firstChar, $initials)) {
				return $shownChar;
			}
		}
		return CoreConfigDisplay::paginationDummy;
	}

	protected function init() {
		$initialChars = $this->controller->getInitialChars();
		if (empty($initialChars)) {
			return;
		}
		if (is_array($initialChars[0])) {
			$this->initialChars = array();
			foreach ($initialChars as $dbRow) {
				$resultStruct = each($dbRow);
				$this->initialChars[] = $resultStruct['value'];
			}
		}
		else {
			$this->initialChars = $initialChars;
		}
		$request = CoreServices::get('request');
		$sessionVarName = '_pagination' . md5($this->address);
		$currentChar = $this->initialChars[0];
		$fromGet = $request->getFromGet('page');
		if (!is_null($fromGet) && in_array($fromGet, $this->initialChars)) {
			$currentChar = $fromGet;
		}
		else {
			$fromSession = $request->getFromSession($sessionVarName);
			if (!empty($fromSession)) {
				$currentChar = $fromSession;
			}
		}
		if (!in_array($currentChar, $this->initialChars)) {
			$this->currentChar = $this->initialChars[0];
		}
		else {
			$this->currentChar = $currentChar;
		}
		$request->setSession($sessionVarName, $this->currentChar);
	}
}
?>