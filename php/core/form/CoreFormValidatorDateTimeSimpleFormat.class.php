<?php
class CoreFormValidatorDateTimeSimpleFormat extends CoreFormAbstractSingleFieldValidator {
	public function __construct($fieldName) {
		parent::__construct($fieldName);
	}

	public function validate($messageManager) {
		$field = $this->form->getField($this->fieldName);
		$fieldValue = $field->getValue();

		if(!empty($fieldValue)) {
			$fieldValArray = explode(' ', $fieldValue);
			if(count($fieldValArray) == 2) {

				$datePart = $fieldValArray[0];
				$timePart = $fieldValArray[1];

				if (!is_null($datePart)) {
					if (date('Y-m-d', strtotime($datePart)) != $datePart) {
						$messageManager->addMessage('invalidDate', array($this->fieldName => $field->getCaption()));
					}
				}

				if (!is_null($timePart)) {
					$parts = explode(':', $timePart);
					for ($i = 0; $i < 3; $i++) {
						if (sizeof($parts) == $i) {
							$parts[$i] = '00';
						}
					}
					for ($i = 0; $i < 3; $i++) {
						$parts[$i] = substr('00' . trim($parts[$i]), -2);
					}
					$timePart = implode(':', $parts);
					if (date('H:i:s', strtotime($timePart)) != $timePart) {
						$messageManager->addMessage('invalidTime', array($this->fieldName => $field->getCaption()));
					}
				}

			} else {
				$messageManager->addMessage('invalidDateTimeFormat', array($this->fieldName => $field->getCaption()));
			}
		}
	}
}
?>