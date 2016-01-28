<?php
class CoreFilterMultipleLikeWithOr extends CoreFilterAbstract {
	protected $fields = null;

	public function __construct($fields, $sortable = true) {
		parent::__construct($sortable);
		$this->fields = $fields;
	}

	public function getFields() {
		return $this->fields;
	}

	public function getConditionType() {
		return 'multipleLikeWithOr';
	}
}
?>