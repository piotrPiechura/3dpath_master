<?php
class CoreFormValidatorStandardSelectCheck extends CoreFormAbstractSingleFieldValidator {
	public function __construct($fieldName) {
		parent::__construct($fieldName);
	}

	public function validate($messageManager) {
		$field = $this->form->getField($this->fieldName);
		$value = $field->getValue();
		if (!is_null($value) && !array_key_exists($value, $field->getPossibleValues())) {
			$messageManager->addMessage('selectFieldValueOutOfRange', array($this->fieldName => $field->getCaption()));
		}
	}
}
?>