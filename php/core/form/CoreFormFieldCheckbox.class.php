<?php
class CoreFormFieldCheckbox extends CoreFormAbstractField {
	public function __construct($name, $defaultValue = null) {
		parent::__construct($name, $defaultValue);
		$this->fieldType = 'Checkbox';
	}

	public function getHTMLValue() {
		return '1';
	}
	
	public function adjustSubmittedValue($submittedValue) {
		if (!is_scalar($submittedValue)) { // assume hacking attempt
			return null;
		}
		if (empty($submittedValue)) {
			return null;
		}
		return '1';
	}
}
?>