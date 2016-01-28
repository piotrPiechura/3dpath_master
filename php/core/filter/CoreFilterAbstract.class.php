<?php

abstract class CoreFilterAbstract {

	protected $sortable = null;
	protected $value = null;

	protected $db = null;

	public function __construct($sortable = true) {
		$this->sortable = $sortable;
		$this->db = CoreServices::get('db');
	}

	public function getValue() {
		return $this->value;
	}

	public function setValue($value) {
		$this->value = $value;
	}

	public function isSortable() {
		return $this->sortable;
	}

	abstract public function getConditionType();

	public function getFieldType() {
		return 'text';
	}

	public function createField($fieldName) {
		return new CoreFormFieldText($fieldName);
	}

}

?>