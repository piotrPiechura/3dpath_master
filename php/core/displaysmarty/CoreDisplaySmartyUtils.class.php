<?php
class CoreDisplaySmartyUtils {
	protected $includedFiles = null;
	
	public function __construct($templateEngine) {
		$this->templateEngine = $templateEngine;
		$this->includedFiles = array();
	}

	public function includeOnce($fileName) {
		if (!empty($this->includedFiles[$fileName])) {
			return;
		}
		$this->includedFiles[$fileName] = 1;
		$fileInfo = pathinfo($fileName);
		$this->templateEngine->assignPrivate('_includeFile', $fileName);
		switch ($fileInfo['extension']) {
			case 'js':
				$this->templateEngine->displayTemplate('utils/include_js.tpl');
				break;
			case 'css':
				$this->templateEngine->displayTemplate('utils/include_css.tpl');
				break;
			default:
				throw new CoreException('This type of files is not supported" \'' . $fileInfo['extension'] . '\'');
		}
		$this->templateEngine->assignPrivate('_includeFile', null);
	}
	
	public function plus() {
		$args = func_get_args();
		$result = 0;
		foreach ($args as $arg) {
			$result += $arg;
		}
		return $result;
	}

	public function minus() {
		$args = func_get_args();
		if (empty($args)) {
			return 0;
		}
		$result = $args[0];
		for ($i = 1; $i < sizeof($args); $i++) {
			$result -= $args[$i];
		}
		return $result;
	}
	
	public function range($x, $y) {
		return range($x, $y);
	}

	public function rangePositive($x, $y) {
		if ($y >= $x) {
			return range($x, $y);
		}
		return array();
	}
	
	public function createArray() {
		return func_get_args();
	}

	public function createAssocArray() {
		$args = func_get_args();
		if (sizeof($args) % 2 != 0) {
			throw new CoreException('Invalid number of arguments for function createArrayKeyValue()');
		}
		$result = array();
		for ($i = 0; $i < sizeof($args); $i += 2) {
			$result[$args[$i]] = $args[$i + 1];
		}
		return $result;
	}
	
	public function arrayMerge($a1, $a2) {
		return array_merge($a1, $a2);
	}

	public function arrayKeys(&$array) {
		return array_keys($array);
	}

	public function insert(&$array, $key, $value) {
		$array[$key] = $value;
	}

	public function push(&$array, $value) {
		$array[] = $value;
	}
	
	public function sizeOf(&$array) {
		return sizeof($array);
	}
	
	public function substring($string, $from, $length = null) {
		if ($length) {
			return substr($string, $from, $length);
		}
		return substr($string, $from);
	}
	
	public function implode($separator, &$array) {
		return implode($separator, $array);
	}

	public function inArray($value, $array) {
		return in_array($value, $array);
	}
	
	public function formatMoney($amount) {
		return number_format($amount, 2);
	}

	public function formatTime($format, $time) {
		return date($format, strtotime($time));
	}

	public function rand($min, $max) {
		return rand($min, $max);
	}

	public function shortText($text, $maxLength) {
		return CoreUtils::shortText($text, $maxLength);
	}

	public function getDateTime() {
		return CoreUtils::getDateTime();
	}

}
?>