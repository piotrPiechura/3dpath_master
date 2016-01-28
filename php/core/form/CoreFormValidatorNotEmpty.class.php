<?php
class CoreFormValidatorNotEmpty extends CoreFormAbstractSingleFieldValidator {
	public function __construct($fieldName) {
		parent::__construct($fieldName);
	}

	public function validate($messageManager) {
		$field = $this->form->getField($this->fieldName);
		$fieldValue = $field->getValue();
		if (empty($fieldValue)) {
			$messageManager->addMessage('emptyField', array($this->fieldName => $field->getCaption()));
		}
	}
}
?>