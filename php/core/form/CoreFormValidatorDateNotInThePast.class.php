<?php
class CoreFormValidatorDateNotInThePast extends CoreFormAbstractSingleFieldValidator {
	public function __construct($fieldName) {
		parent::__construct($fieldName);
	}

	public function validate($messageManager) {
		$field = $this->form->getField($this->fieldName);
		$fieldValue = $field->getValue();
		if (!is_null($fieldValue)) {
			if (date('Y-m-d', strtotime($fieldValue)) < CoreUtils::getDate()) {
				$messageManager->addMessage('dateInThePast', array($this->fieldName => $field->getCaption()));
			}
		}
	}
}
?>