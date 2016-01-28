<?php
class CoreFormFieldMultiselect extends CoreFormAbstractField {
	protected $possibleValues = null;

	/**
	 * $possibleValues is an array $value => $description.
	 */
	public function __construct($name, $defaultValue = null, $possibleValues = null) {
		parent::__construct($name, $defaultValue);
		$this->fieldType = 'Multiselect';
		$this->possibleValues = $possibleValues;
	}

	/**
	 * Returns an array $value => $description.
	 */
	public function getPossibleValues() {
		return $this->possibleValues;
	}

	public function setValue($value) {
		parent::setValue($value);
	}

	public function adjustSubmittedValue($submittedValue) {
		if (!is_array($submittedValue)) { // assume hacking attempt
			return array();
		}
		$returnValue = array();
		foreach ($submittedValue as $simpleValue) {
			if (is_scalar($simpleValue)) { // else assume hacking attempt
				$returnValue[] = htmlspecialchars($simpleValue, ENT_QUOTES, CoreConfig::get('CoreDisplay', 'globalCharset'));
			}
		}
		return $returnValue;
	}
}
?>