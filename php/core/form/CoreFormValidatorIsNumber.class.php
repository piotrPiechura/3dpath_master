<?php
class CoreFormValidatorIsNumber extends CoreFormAbstractSingleFieldValidator {
	public function __construct($fieldName) {
		parent::__construct($fieldName);
	}

	public function validate($messageManager) {
		$field = $this->form->getField($this->fieldName);
		$fieldValue = $field->getValue();
		if (!is_numeric($fieldValue)) {
			$messageManager->addMessage('notANumber', array($this->fieldName => $field->getCaption()));
		}
	}
}
?>