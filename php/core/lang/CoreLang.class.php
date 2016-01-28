<?php
class CoreLang {
	protected $langs = null;

	public function __construct() {
		$this->langs = array();
	}

	public function getLang($index) {
		if (empty($this->langs[$index])) {
			$lang = CoreServices::get('request')->getFromRequest('_lang' . $index);
			// throws exception if there's no such config variable like 'langs' . $index
			$availableLangs = CoreConfig::get('CoreLangs', 'langs' . $index);
			// throws exception if there's no such config variable like 'defaultLang' . $index
			$defaultLang = CoreConfig::get('CoreLangs', 'defaultLang' . $index);
			if (!in_array($lang, $availableLangs)) {
				$lang = $availableLangs[0];
			}
			if (!$lang) {
				$lang = $defaultLang;
			}
			$this->langs[$index] = $lang;
			CoreServices::get('request')->setSession('_lang' . $index, $this->langs[$index]);
		}
		return $this->langs[$index];
	}

	public function getLangs($index, $firstLang = null) {
		$availableLangs = CoreConfig::get('CoreLangs', 'langs' . $index);
		if (!is_null($firstLang)) {
			if (!in_array($firstLang, $availableLangs)) {
				throw new CoreException('Invalid language: \'' . $firstLang . '\'');
			}
			$result = array(0 => $firstLang);
			foreach ($availableLangs as $someLang) {
				if ($someLang != $firstLang) {
					$result[] = $someLang;
				}
			}
			return $result;
		}
		else {
			$result = $availableLangs;
		}
		return $result;
	}

	public function getOtherLangs($index, $lang) {
		$allLangs = $this->getLangs($index);
		$otherLangs = array();
		foreach ($allLangs as $someLang) {
			if ($someLang != $lang) {
				$otherLangs[] = $someLang;
			}
		}
		return $otherLangs;
	}
}
?>