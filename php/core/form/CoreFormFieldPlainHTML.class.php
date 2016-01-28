<?php
class CoreFormFieldPlainHTML extends CoreFormAbstractField {
	public function __construct($name, $defaultValue = null) {
		parent::__construct($name, $defaultValue);
		$this->fieldType = 'PlainHTML';
	}

	public function getHTMLValue() {
		return htmlspecialchars($this->value, ENT_QUOTES);
	}
	
	public function adjustSubmittedValue($submittedValue) {
		if (!is_scalar($submittedValue)) { // assume hacking attempt
			return null;
		}
		$submittedValue = trim($submittedValue);
		if (empty($submittedValue)) {
			return null;
		}
		return $submittedValue;
	}
}
?>