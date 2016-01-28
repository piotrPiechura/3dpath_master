<?php
class AdminCMSEditController extends CMSAbstractControllerEdit {
	public function getMenuItemDescription() {
		return 'Admin';
	}

	protected function getDAOClass() {
		return 'AdminDAO';
	}

	protected function isControllerUsagePermitted() {
		return (!empty($this->currentUser['id']) && ($this->currentUser['adminRole'] >= $this->adminRoles['adminRoleDataAdmin']));
	}

	protected function checkUserPermissionsForRecord() {
		if (
			$this->currentUser['adminRole'] < $this->adminRoles['adminRoleSuperadmin']
			&& $this->currentUser['id'] != $this->record['id']
		) {
			CoreServices2::getDB()->transactionCommit();
			CoreUtils::redirect($this->getListPageAddress());
		}
	}

	protected function initMultiselectRelations() {}

	protected function initActions() {
		$this->availableActions = array();
		if (
			$this->currentUser['id'] == $this->record['id']
			|| $this->currentUser['adminRole'] == $this->adminRoles['adminRoleSuperadmin']
		) {
			$this->availableActions[] = 'Save';
		}
		if (
			$this->currentUser['adminRole'] == $this->adminRoles['adminRoleSuperadmin']
			&& $this->record['id'] != $this->currentUser['id']
		) {
			if ($this->record['adminState'] != 'active') {
				$this->availableActions[] = 'Activate';
			}
			else {
				$this->availableActions[] = 'Block';
			}
			if (
				!empty($this->record['id'])
				&& !$this->dao->hasRelatedRecords($this->record)
			) {
				$this->availableActions[] = 'Delete';
			}
		}
	}

	protected function createFormFields() {
		parent::createFormFields();
		if (empty($this->record['id'])) {
			$this->form->addField(new CoreFormFieldText('adminName'));
		}
		$this->form->addField(new CoreFormFieldPassword('adminPassword'));
		$this->form->addField(new CoreFormFieldPassword('adminPasswordConfirm'));
		if (
			$this->currentUser['adminRole'] == $this->adminRoles['adminRoleSuperadmin']
		) {
			$this->form->addField(new CoreFormFieldText('adminFirstName'));
			$this->form->addField(new CoreFormFieldText('adminSurname'));
			if ($this->record['id'] != $this->currentUser['id']) {
				$this->form->addField(new CoreFormFieldSelect(
					'adminRole',
					null,
					array(0 => '<choose>') + CoreConfig::get('Data', 'adminRoles')
				));
			}
		}
	}

	protected function addFormValidators() {
		if ($this->form->hasField('adminName')) {
			$this->form->addValidator(new CoreFormValidatorNotEmpty('adminName'));
			$this->form->addValidator(new CoreFormValidatorMaxTextLength('adminName', 40));
			$this->form->addValidator(new AdminNameValidator('adminName', 'id', $this->record['adminName']));
		}
		$this->form->addValidator(new CoreFormValidatorFieldsEqual(array('adminPassword', 'adminPasswordConfirm')));
		$this->form->addValidator(new CoreFormValidatorOldValueIfEmpty('adminPassword', 'id', $this->record['adminPassword']));
		$this->form->addValidator(new CoreFormValidatorPasswordPattern('adminPassword'));
		if ($this->form->hasField('adminRole')) {
			$this->form->addValidator(new CoreFormValidatorNotEmpty('adminFirstName'));
			$this->form->addValidator(new CoreFormValidatorMaxTextLength('adminFirstName', 80));
			$this->form->addValidator(new CoreFormValidatorNotEmpty('adminSurname'));
			$this->form->addValidator(new CoreFormValidatorMaxTextLength('adminSurname', 80));
			$this->form->addValidator(new CoreFormValidatorNotEmpty('adminRole'));
		}
	}

	protected function handleActivateRequest() {
		$this->errorMessageContainer = $this->form->getValidationResults();
		if (!$this->errorMessageContainer->isAnyErrorMessage()) {
			$this->setRecordValuesFromForm();
			$this->setSpecialRecordFieldsBeforeSave();
			$this->record['adminState'] = 'active';
			$this->dao->save($this->record);
			$this->afterSave();
			$this->setRedirectAddress(
				CoreServices::get('url')->getCurrentPageUrl('id', $this->record['id'], '_sm', 'Save')
			);
		}
	}

	protected function handleBlockRequest() {
		$this->errorMessageContainer = $this->form->getValidationResults();
		if (!$this->errorMessageContainer->isAnyErrorMessage()) {
			$this->setRecordValuesFromForm();
			$this->setSpecialRecordFieldsBeforeSave();
			$this->record['adminState'] = 'blocked';
			$this->dao->save($this->record);
			$this->afterSave();
			$this->setRedirectAddress(
				CoreServices::get('url')->getCurrentPageUrl('id', $this->record['id'], '_sm', 'Save')
			);
		}
	}
					
	protected function runRequestHandler() {
		$action = $this->getAction();
		switch ($action) {
			case 'Save':
				$this->handleSaveRequest();
				break;
			case 'Delete':
				$this->handleDeleteRequest();
				break;
			case 'Activate':
				$this->handleActivateRequest();
				break;
			case 'Block':
				$this->handleBlockRequest();
				break;
		}
	}

	protected function setSpecialRecordFieldsBeforeSave() {
		if(empty($this->record['id'])) {
			$this->record['adminState'] = 'blocked';
		}
	}
}
?>