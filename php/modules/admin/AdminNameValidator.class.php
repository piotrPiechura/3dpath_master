<?php
class AdminNameValidator extends CoreFormAbstractSingleFieldValidator {
	protected $idFieldName = null;
	protected $oldValue = null;

	public function __construct($fieldName, $idFieldName, $oldValue) {
		parent::__construct($fieldName);
		$this->idFieldName = $idFieldName;
		$this->oldValue = $oldValue;
	}

	public function validate($messageManager) {
		$recordId = $this->form->getField($this->idFieldName)->getValue();
		$field = $this->form->getField($this->fieldName);
		$fieldValue = $field->getValue();
		if (empty($recordId) || $fieldValue != $this->oldValue) {
			if ($fieldValue) {
				$dao = new AdminDAO();
				if ($dao->isRegisteredAdmin($fieldValue)) {
					$messageManager->addMessage('userNameAlreadyRegistered', array($this->fieldName => $field->getCaption()));
				}
			}
		}
	}
}
?>