<?php
class CoreFormValidatorPasswordPattern extends CoreFormAbstractSingleFieldValidator {
	public function __construct($fieldName) {
		parent::__construct($fieldName);
	}

	public function validate($messageManager) {
		$field = $this->form->getField($this->fieldName);
		$value = $field->getOpenText();
		if (empty($value)) {
			return;
		}
		if (
			strlen($value) < 6
			|| !preg_match('/[a-zA-Z' . implode('', CoreConfig::get('CoreLangs', 'localCharsToLatinSource')) . ']/', $value)
			|| !preg_match('/[0-9]/', $value)
		) {
			$messageManager->addMessage('newPasswordTooWeak', array($this->fieldName => $field->getCaption()));
			return;
		}
	}
}
?>