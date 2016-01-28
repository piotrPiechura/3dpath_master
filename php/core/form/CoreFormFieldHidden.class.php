<?php
class CoreFormFieldHidden extends CoreFormAbstractField {
	public function __construct($name, $defaultValue = null) {
		parent::__construct($name, $defaultValue);
		$this->fieldType = 'Hidden';
	}
	
	public function adjustSubmittedValue($submittedValue) {
		if (!is_scalar($submittedValue)) { // assume hacking attempt
			//return '';
			return null;
		}
		return htmlspecialchars($submittedValue, ENT_QUOTES, CoreConfig::get('CoreDisplay', 'globalCharset'));
	}
}
?>