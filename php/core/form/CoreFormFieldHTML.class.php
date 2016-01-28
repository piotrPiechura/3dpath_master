<?php
class CoreFormFieldHTML extends CoreFormAbstractField {
	public function __construct($name, $defaultValue = null) {
		parent::__construct($name, $defaultValue);
		$this->fieldType = 'HTML';
	}

	public function getHTMLValue() {
		return $this->value;
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