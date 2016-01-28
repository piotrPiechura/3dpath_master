<?php

class CoreFilterSelect extends CoreFilterExact {

	protected $selectOptions = null;

	public function __construct($sortable = true, $selectOptions = null) {
		parent::__construct($sortable);
		$this->selectOptions = $selectOptions;
	}

	public function getValue() {
		if(!empty($this->value) && isset($this->selectOptions[$this->value])) {
			return $this->value;
		} else {
			return null;
		}
	}

	public function setValue($value) {
		$this->value = $value;
	}

	public function getConditionType() {
		return 'select';
	}

	public function getFieldType() {
		return 'select';
	}

	public function createField($fieldName) {
		return new CoreFormFieldSelect($fieldName, null, $this->selectOptions);
	}

}

?>