<?php
class CoreFormValidatorTimeSimpleFormat extends CoreFormAbstractSingleFieldValidator {
	public function __construct($fieldName) {
		parent::__construct($fieldName);
	}

	public function validate($messageManager) {
		$field = $this->form->getField($this->fieldName);
		$fieldValue = $field->getValue();
		if (!is_null($fieldValue)) {
			$parts = explode(':', $fieldValue);
			for ($i = 0; $i < 3; $i++) {
				if (sizeof($parts) == $i) {
					$parts[$i] = '00';
				}	
			}
			for ($i = 0; $i < 3; $i++) {
				$parts[$i] = substr('00' . trim($parts[$i]), -2);
			}
			$fieldValue = implode(':', $parts);
			if (date('H:i:s', strtotime($fieldValue)) != $fieldValue) {
				$messageManager->addMessage('invalidDate', array($this->fieldName => $field->getCaption()));
			}
			else {
				$field->setValue($fieldValue);
			}
		}
	}
}
?>