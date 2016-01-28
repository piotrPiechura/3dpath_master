<?php
class CoreFormFieldSelect extends CoreFormAbstractField {
	protected $possibleValues = null;

	/**
	 * $possibleValues is an array $value => $description.
	 */
	public function __construct($name, $defaultValue = null, $possibleValues = null) {
		parent::__construct($name, $defaultValue);
		$this->fieldType = 'Select';
		if (empty($possibleValues)) {
			$this->possibleValues = array();
		}
		else {
			$this->possibleValues = $possibleValues;
		}
		if (!is_null($this->value) && !array_key_exists($this->value, $this->possibleValues)) {
			throw new CoreException('Default value out of range in select field \'' . $name . '\'.');
		}
	}

	/**
	 * Returns an array $value => $description.
	 */
	public function getPossibleValues() {
		return $this->possibleValues;
	}

	public function adjustSubmittedValue($submittedValue) {
		if (!is_scalar($submittedValue)) { // assume hacking attempt
			return null;
		}
		return htmlspecialchars($submittedValue, ENT_QUOTES, CoreConfig::get('CoreDisplay', 'globalCharset'));
	}

	public function getHTMLValue() {
		if (empty($this->possibleValues)) {
			return null;
		}
		if (array_key_exists($this->value, $this->possibleValues)) {
			return $this->value;
		}
		$values = array_keys($this->possibleValues);
		return $values[0];
	}
}
?>