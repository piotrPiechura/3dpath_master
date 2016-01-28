<?php
include_once('php/external/emailaddressvalidator/EmailAddressValidator.class.php');

class CoreFormValidatorEmail extends CoreFormAbstractSingleFieldValidator {
	public function __construct($fieldName) {
		parent::__construct($fieldName);
	}

	public function validate($messageManager) {
		$field = $this->form->getField($this->fieldName);
		$fieldValue = $field->getValue();
		if (!empty($fieldValue)) {
			$externalValidator = new EmailAddressValidator();
			if (!$externalValidator->check_email_address($fieldValue)) {
				$messageManager->addMessage('invalidEmail', array($this->fieldName => $field->getCaption()));
			}
		}
	}
}
?>