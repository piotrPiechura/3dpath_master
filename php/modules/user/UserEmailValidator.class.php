<?php
class UserEmailValidator extends CoreFormValidatorEmail {
	protected $idFieldName = null;
	protected $oldValue = null;

	public function __construct($fieldName, $idFieldName, $oldValue) {
		parent::__construct($fieldName);
		$this->idFieldName = $idFieldName;
		$this->oldValue = $oldValue;
	}

	public function validate($messageManager) {
		parent::validate($messageManager);
		if (!$messageManager->isAnyErrorMessage()) {
			$recordId = $this->form->getField($this->idFieldName)->getValue();
			$field = $this->form->getField($this->fieldName);
			$fieldValue = $field->getValue();
			if (empty($recordId) || $fieldValue != $this->oldValue) {
				if (!empty($fieldValue)) {
					$dao = new UserDAO();
					if ($dao->isRegisteredUser($fieldValue)) {
						$messageManager->addMessage('userEmailAlreadyRegistered', array($this->fieldName => $field->getCaption()));
					}
				}
			}
		}
	}
}
?>