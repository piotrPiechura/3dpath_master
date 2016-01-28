<?php
class CoreFormFieldSubmit extends CoreFormAbstractField {
	public function __construct($name) {
		parent::__construct($name, null);
		$this->fieldType = 'Submit';
	}
	
	public function adjustSubmittedValue($submittedValue) {
		return ($submittedValue ? '1' : null);
	}
}
?>