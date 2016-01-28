<?php
class UserCMSEditController extends CMSAbstractControllerEditWithFileUpload {

	public function getMenuItemDescription() {
		return 
			empty($this->record['userEraseRequestTime'])
			? 'User'
			: 'UserDeletion';
	}

	protected function getDAOClass() {
		return 'UserDAO';
	}
	
	protected function initMultiselectRelations() {}

	protected function checkUserPermissionsForRecord() {
		if ($this->record['userState'] == 'deleted') {
			CoreUtils::redirect($this->getListPageAddress());
		}
	}
	
	protected function initActions() {
		$this->availableActions = array();
		if (empty($this->record['id'])) {
			$this->availableActions = array('Save');
		}
		else {
			switch ($this->record['userState']) {
				case 'active':
					$this->availableActions = array('Save', 'Block', 'DeleteAll');
					break;
				case 'blocked':
					$this->availableActions = array('Save', 'Activate', 'DeleteAll');
					break;
				case 'forDeletion':
					$this->availableActions = array('DeleteAll');
					break;
				default:
					throw new CoreException('Invalid state of user record');
			}
		}
	}

	protected function createCountryField() {
		$dao = new CountryDAO();
		$recordList = $dao->getSimpleList();
		$options = array(0 => '<choose>') + $dao->modifyListForSelect(
			$recordList,
			'<countryName>'
		);
		$this->form->addField(new CoreFormFieldSelect(
			'countryId',
			null,
			$options
		));
	}

	protected function createFormFields() {
		parent::createFormFields();
		if (empty($this->record['id'])) {
			$this->form->addField(new CoreFormFieldText('userEmail'));
		}
		$this->form->addField(new CoreFormFieldPassword('userPassword'));
		$this->form->addField(new CoreFormFieldPassword('userPasswordConfirm'));
		$this->form->addField(new CoreFormFieldText('userNick'));
		$this->form->addField(new CoreFormFieldText('userSignature'));
		$this->form->addField(new CoreFormFieldText('userCompanyName'));
		$this->form->addField(new CoreFormFieldText('userFirstName'));
		$this->form->addField(new CoreFormFieldText('userSurname'));
		$this->form->addField(new CoreFormFieldText('userAddressStreet'));
		$this->form->addField(new CoreFormFieldText('userAddressCity'));
		$this->form->addField(new CoreFormFieldText('userAddressRegion'));
		$this->form->addField(new CoreFormFieldText('userZipCode'));
		$this->createCountryField();
		$this->form->addField(new CoreFormFieldText('userTaxIdentifier'));
	}

	protected function addFormValidators() {
		parent::addFormValidators();
		$this->form->addValidator(new UserValidatorBasic($this->record));
	}

	protected function setSpecialRecordFieldsBeforeSave() {
		if (empty($this->record['id'])) {
			$this->record['userState'] = 'active';
			$this->record['userRegisterTime'] = CoreUtils::getDateTime();
			$this->record['userCredits'] = 0;
		}
	}

	protected function afterSave() {
		parent::afterSave();
		$optimaInterface = Optima_Interface::getInstance();
		$optimaInterface->updateUserData($this->record);
	}

	protected function handleActivateRequest() {
		$this->saveWithStatusChange('userState', 'active', 'Activate');
	}

	protected function handleBlockRequest() {
		$this->saveWithStatusChange('userState', 'blocked', 'Block');
	}

	protected function deleteUsersClipboards() {
		$clipboardDAO = new ClipboardDAO();
		$list = $clipboardDAO->getListByForeignKey('userId', $this->record['id']);
		foreach ($list as $clipboard) {
			$clipboardDAO->deleteCascade($record);
		}
	}

	protected function deleteUsersFiles() {
		$dao = new FileDAO();
		$list = $dao->getListByRecord('user', $this->record['id']);
		foreach ($list as $record) {
			$dao->delete($record);
		}
	}

	protected function handleDeleteAllRequest() {
		// Ogólnie nie usuwamy rekordów zależnych, ponieważ tak naprawdę
		// nie usuwamy użytkownika, tylko czyścimy jego dane osobowe.
		// Pozostają jego downloady.
		// Pozostaja pakiety.
		// Pozostaja jego wątki i posty na forum.
		// Pozostaje autor.
		// Czyścimy pliki.
		// Czyścimy clipboard.
		$this->deleteUsersClipboards();
		$this->deleteUsersFiles();
		$this->dao->delete($this->record);
		$this->setRedirectAddress(
			$this->getListPageAddress(array('_sm', 'Delete'))
		);
	}

	protected function getListPageAddress($args = null) {
		if (is_null($args)) {
			$args = array();
		}
		$basicArgs =
			empty($this->record['userEraseRequestTime'])
			? array('_m', 'User', '_o', 'CMSList')
			: array('_m', 'User', '_o', 'CMSListForDeletion');
		$args = array_merge($basicArgs, $args);
		return CoreServices::get('url')->createAddress($args);
	}

	protected function runRequestHandler() {
		$action = $this->getAction();
		switch ($action) {
			case 'Save':
				$this->handleSaveRequest();
				break;
			case 'Activate':
				$this->handleActivateRequest();
				break;
			case 'Block':
				$this->handleBlockRequest();
				break;
			case 'DeleteAll':
				$this->handleDeleteAllRequest();
				break;
		}
	}

	public function assignDisplayVariables() {
		parent::assignDisplayVariables();
		$display = CoreServices2::getDisplay();
		$display->assign(
			'listPageAddress',
			CoreServices2::getUrl()->getHTMLVersion($this->getListPageAddress())
		);
	}
}
?>