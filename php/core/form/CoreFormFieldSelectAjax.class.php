<?php
class CoreFormFieldSelectAjax extends CoreFormAbstractField {
	/**
	 * Possible values are taken from AJAX.
	 * Parameter $pattern is passed to function CoreModelAbstractDAO::modifyListForSelectBySpecificColumn();
	 * - see there for more info;
	 * The same for $emptyRowDescriptionPattern.
	 * Parameter $valueColumn is a name of the column that contains values of options, if different than 'id'.
	 */
	public function __construct($name, $defaultValue = null) {
		parent::__construct($name, $defaultValue);
		$this->fieldType = 'SelectAJAX';
	}

	/**
	 * Returns an array $value => $description.
	 */
	public function getPossibleValues() {
		if (!is_null($this->value)) {
			// This is required by ajax for initilization;
			// the user will not see the description
			return array($this->value => '---');
		}
		return array();
	}

	public function adjustSubmittedValue($submittedValue) {
		if (!is_scalar($submittedValue)) { // assume hacking attempt
			return null;
		}
		return htmlspecialchars($submittedValue, ENT_QUOTES, CoreConfig::get('CoreDisplay', 'globalCharset'));
	}
}
?>