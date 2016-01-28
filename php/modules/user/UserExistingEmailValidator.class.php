<?php
class UserExistingEmailValidator extends CoreFormAbstractSingleFieldValidator {
	public function __construct($fieldName) {
		parent::__construct($fieldName);
	}

	public function validate($messageManager) {
		$field = $this->form->getField($this->fieldName);
		$fieldValue = $field->getValue();
		if ($fieldValue) {
			$dao = new UserDAO();
			if (!$dao->isActiveUser($fieldValue)) {
				$messageManager->addMessage('userDoesNotExist', array($this->fieldName => $field->getCaption()));
			}
		}
	}
}
?>