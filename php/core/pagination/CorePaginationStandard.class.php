<?php
class CorePaginationStandard implements iCorePagination {
	protected $controller = null;
	protected $address = null;
	protected $recordCount = null;
	protected $maxRecordsOnPage = null;

	protected $pageCount = null;
	protected $currentPage = null;

 	public function __construct($controller, $address, $maxRecordsOnPage = null, $maxShownPages = null) {
		$this->controller = $controller;
		$this->address = CoreServices::get('url')->stripParams($address, array('page'));
		if (!empty($maxRecordsOnPage)) {
			$this->maxRecordsOnPage = $maxRecordsOnPage;
		}
		else {
			$this->maxRecordsOnPage = CoreConfig::get('CoreDisplay', 'paginationDefaultMaxRecords');
		}
		if (!empty($maxShownPages)) {
			$this->maxShownPages = $maxShownPages;
		}
		else {
			$this->maxShownPages = CoreConfig::get('CoreDisplay', 'paginationDefaultMaxPageLinks');
		}
		$this->init();
	}

	public function getType() {
		return 'Standard';
	}

	public function getPageCount() {
		return $this->pageCount;
	}

	public function enforceCurrentPage($page) {
		// ponieważ nie ma możliwości wywołania tej funkcji przed
		// zainicjalizowaniem obiektu, currentPage juz nie zostanie
		// nadpisane, więc nie trzeba dodatkowo pamiętać że wartość
		// jest wymuszona
		$this->currentPage = $page;
	}

	public function enforceCurrentPageByRecordOffset($recordOffset) {
		$page = min(
			floor($recordOffset / $this->maxRecordsOnPage),
			$this->pageCount - 1
		);
		$this->enforceCurrentPage($page);
	}

	public function getCurrentPage() {
		return $this->currentPage;
	}
	
	public function getCurrentOffset() {
		return floor($this->currentPage * $this->maxRecordsOnPage);
	}
	
	public function getMaxRecordsOnPage() {
		return $this->maxRecordsOnPage;
	}

	public function getRecordCount() {
		return $this->recordCount;
	}
	
	public function getLinksHTML() {
		$url = CoreServices::get('url');
		$result = array();
		$maxPagesBefore = floor(($this->maxShownPages - 1) / 2);
		$maxPagesAfter = ceil(($this->maxShownPages - 1) / 2);
		$firstPage = max(0, $this->currentPage - $maxPagesBefore);
		$lastPage = min($this->pageCount - 1, $this->currentPage + $maxPagesAfter);
		for ($i = $firstPage; $i <= $lastPage; $i++) {
			$result[$i] = $url->appendArgumentsHTML($this->address, 'page', $i);
		}
		return $result;
	}

	public function getFirstPageLinkHTML() {
		return CoreServices::get('url')->appendArgumentsHTML($this->address, 'page', 0);
	}

	public function getLastPageLinkHTML() {
		return CoreServices::get('url')->appendArgumentsHTML($this->address, 'page', ($this->pageCount - 1));
	}

	public function getPreviousPageLinkHTML() {
		if ($this->currentPage > 0) {
			return CoreServices::get('url')->appendArgumentsHTML($this->address, 'page', $this->currentPage - 1);
		}
		return null;
	}

	public function getNextPageLinkHTML() {
		if ($this->currentPage < $this->pageCount - 1) {
			return CoreServices::get('url')->appendArgumentsHTML($this->address, 'page', $this->currentPage + 1);
		}
		return null;
	}

	protected function init() {
		$this->recordCount = $this->controller->getRecordCount();
		$this->pageCount = ceil($this->recordCount / $this->maxRecordsOnPage);
		$request = CoreServices::get('request');
		$sessionVarName = '_pagination' . md5($this->address);
		$currentPage = 0;
		$fromGet = $request->getFromGet('page');
		if (!is_null($fromGet) && is_numeric($fromGet) && round($fromGet) == $fromGet && $fromGet >= 0 && $fromGet < $this->pageCount) {
			$currentPage = intval($fromGet);
		}
		else {
			$fromSession = $request->getFromSession($sessionVarName);
			if (!empty($fromSession)) {
				$currentPage = $fromSession;
			}
		}
		if ($currentPage < 0) {
			$this->currentPage = 0;
		}
		elseif ($currentPage > $this->pageCount - 1) {
			$this->currentPage = max($this->pageCount - 1, 0);
		}
		else {
			$this->currentPage = $currentPage;
		}
		$request->setSession($sessionVarName, $this->currentPage);
	}
}
?>