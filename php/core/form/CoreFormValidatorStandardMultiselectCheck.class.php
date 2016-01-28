<?php
class CoreFormValidatorStandardMultiselectCheck extends CoreFormAbstractSingleFieldValidator {
	public function __construct($fieldName) {
		parent::__construct($fieldName);
	}

	public function validate($messageManager) {
		$field = $this->form->getField($this->fieldName);
		foreach ($field->getValue() as $value) {
			if (!array_key_exists($value, $field->getPossibleValues())) {
				$messageManager->addMessage('selectFieldValueOutOfRange', array($this->fieldName => $field->getCaption()));
			}
		}
	}
}
?>