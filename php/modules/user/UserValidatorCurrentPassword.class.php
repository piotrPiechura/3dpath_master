<?php
/**
 *
 */
class UserValidatorCurrentPassword extends CoreFormAbstractSingleFieldValidator {

	protected $userId = null;

	public function __construct($fieldName, $userId) {
		parent::__construct($fieldName);
		$this->userId = $userId;
	}

	public function validate($messageManager) {
		$field = $this->form->getField($this->fieldName);
		$fieldValue = $field->getValue();

		$userDAO = new UserDAO();
		$userRecord = $userDAO->getRecordById($this->userId);
		if ($userRecord['userPassword'] != $fieldValue) {
			$messageManager->addMessage('invalidPassword', array($this->fieldName => $field->getCaption()));
		}
	}
}
?>