<?php
class CoreFormFieldText extends CoreFormAbstractField {
	public function __construct($name, $defaultValue = null) {
		parent::__construct($name, $defaultValue);
		$this->fieldType = 'Text';
	}

	public function getHTMLValue() {
		return str_replace('<br/>', "\n", $this->value);
	}

	public function adjustSubmittedValue($submittedValue) {
		if (!is_scalar($submittedValue)) { // assume hacking attempt
			//return '';
			return null;
		}
		$submittedValue = trim($submittedValue);
		if (empty($submittedValue)) {
			return null;
		}
		$submittedValue = htmlspecialchars($submittedValue, ENT_QUOTES, CoreConfig::get('CoreDisplay', 'globalCharset'));
		$submittedValue = str_replace("\n", '<br/>', $submittedValue);
		return $submittedValue;
	}
}
?>