<?php
class UserWebsiteEditController extends WebsiteAbstractControllerStandardLayout {
	protected $dao = null;
	protected $record = null;
	protected $recordOldValues = null;
	protected $form = null;
	protected $errorMessageContainer = null;
	protected $successMessageType = null;
	protected $redirectAddress = null;

	protected $handledFileLists = null;
	protected $fileList = null;
	protected $fileListOldValues = null;

	public function initSubpage() {}

	public function prepareData() {
		parent::prepareData();
		$this->dao = new UserDAO();
		$this->initRecord();
		$this->handledFileLists = $this->getHandledFileLists();
		$this->initFiles();
		$this->initForm();
		$this->createFormFields();
		if ($this->form->isSubmitted()) {
			$this->form->setFieldValuesFromRequest();
			$this->addFormValidators();
			$this->handleRequest();
		}
		else {
			$this->setFormValuesFromRecord();
			$this->successMessageType = CoreServices::get('request')->getFromGet('_sm');
		}		
		if (!empty($this->redirectAddress)) {
			$this->redirectToPage($this->redirectAddress, 'standard');
		}
	}

	protected function getRecordType() {
		return 'user';
	}

	protected function isUsagePermitted() {
		return $this->isUserLogged();
	}

	protected function initRecord() {
		$this->record = $this->dao->getRecordById($this->currentUser['id']);
		if (!$this->record['id']) {
			$logoutUrl = CoreServices2::getUrl()->createAddress(
				'_m', 'Home',
				'_o', 'Website',
				'logout', '1'
			);
			$this->redirectToPage($logoutUrl, 'standard');
		}
		$this->recordOldValues = $this->record; // clone!
	}

	protected function initForm() {
		$this->form = new CoreFormWithVLFs('post', null, 'mainForm');
	}

	protected function getHandledFileLists() {
		return array(
			'avatar' => 'image'
		);
	}

	protected function initFiles() {
		if ($this->record['id']) {
			$this->fileList = array();
			$this->fileListOldValues = array();
			$this->fileList['avatar'] = $this->fileDAO->getListByRecord(
				$this->getRecordType(),
				$this->record['id'],
				'image',
				'avatar'
			);
			$this->fileListOldValues['avatar'] = array();
			foreach ($this->fileList['avatar'] as $record) {
				$this->fileListOldValues['avatar'][$record['id']] = $record; // clone
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
		$this->form->addField(new CoreFormFieldHidden('id'));
		$this->form->addField(new CoreFormFieldPassword('userPasswordOld'));
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

		$this->form->addFieldToVLF('avatar', new CoreFormFieldHidden('id'));
		$this->form->addFieldToVLF('avatar', new CoreFormFieldHidden('fileOrder'));
		$this->form->addFieldToVLF('avatar', new CoreFormFieldFile('_fileUpload'));
	}

	protected function addFormValidators() {
		$this->form->addValidator(new UserValidatorBasic($this->record));

		$newPassword1Val = $this->form->getField('userPassword')->getValue();
		$newPassword2Val = $this->form->getField('userPasswordConfirm')->getValue();
		if(!empty($newPassword1Val) || !empty($newPassword2Val)) {
			$this->form->addValidator(new CoreFormValidatorNotEmpty('userPasswordOld'));
			$this->form->addValidator(new UserValidatorCurrentPassword('userPasswordOld', CoreServices2::getAccess()->getCurrentUserId()));
		}

		$this->form->addValidator(new CoreFormValidatorVLFRecordListConsistency(
			'avatar',
			$this->fileListOldValues['avatar']
		));
		$this->form->addValidatorForVLF('avatar', new CoreFormValidatorFileBasicCheck('_fileUpload', 'id', 'image'));
		$this->form->addValidatorForVLF('avatar', new CoreFormValidatorImageFile('_fileUpload'));
		$this->form->addValidator(new CoreFormValidatorVLFLength('avatar', 0, 1));
	}

	protected function setFormValuesFromRecord() {
		$this->form->setFieldValuesFromRecord($this->record);

		$this->form->setVLFValuesFromRecords('avatar', $this->fileList['avatar']);
	}

	protected function setRecordValuesFromForm() {
		$this->form->setRecordValuesFromFields($this->record);
		$templateFileRecord = $this->fileDAO->getRecordTemplate();

		$this->fileList['avatar'] = $this->form->getRecordsFromVLF(
			'avatar',
			$templateFileRecord,
			$this->fileListOldValues['avatar']
		);
	}

	protected function saveFileLists() {
		foreach ($this->fileList['avatar'] as $index => $record) {
			if (empty($record['_inactive'])) {
				$record['recordType'] = $this->getRecordType();
				$record['recordId'] = $this->record['id'];
				$record['filePosition'] = 'avatar';
				$record['fileCategory'] = 'image';
				$uploadStruct = $this->form->getField('avatar' . '[' . $index . '][_fileUpload]')->getValue();
				$this->fileDAO->save($record, $uploadStruct);
			}
			elseif (!empty($record['id'])) {
				$this->fileDAO->delete($record);
			}
		}
	}

	protected function handleRequest() {
		$this->errorMessageContainer = $this->form->getValidationResults();
		if (!$this->errorMessageContainer->isAnyErrorMessage()) {
			$this->setRecordValuesFromForm();
			$this->dao->save($this->record);
			$this->saveFileLists();
                        $optimaInterface = Optima_Interface::getInstance();
                        $optimaInterface->updateUserData($this->record);
			// Trzeba odświeżyć dane o użytkowniku przechowywane w sesji
			CoreServices2::getAccess()->logout();
			if (!CoreServices2::getAccess()->login(
				$this->record['userEmail'],
				$this->record['userPassword']
			)) {
				throw new CoreException('Unable to refresh user data stored in session');
			}
			else {
				$this->redirectAddress = CoreServices2::getUrl()->getCurrentPageUrl('_sm', 'Save');
			}
		}
	}

	public function assignDisplayVariables() {
		parent::assignDisplayVariables();
		$display = CoreServices2::getDisplay();
		$display->assign('mainForm', $this->form);
		if (!empty($this->errorMessageContainer) && $this->errorMessageContainer->isAnyErrorMessage()) {
			$display->assign('formErrorMessages', $this->errorMessageContainer);
		}
		$display->assign('successMessageType', $this->successMessageType);
		if ($this->recordOldValues) {
			$display->assign('recordOldValues', $this->recordOldValues);
		}
		$display->assign('avatar', $this->fileList['avatar']);
		$display->assign('avatarOldValues', $this->fileListOldValues['avatar']);
	}
}
?>