<?php
class SettingsCMSEditController extends CMSAbstractControllerEdit {

	public function getMenuItemDescription() {
		return 'Settings';
	}

	protected function getDAOClass() {
		return 'SettingsDAO';
	}

	protected function initRecord() {
		$id = CoreServices::get('request')->getFromRequest('id');
		if (!empty($id)) {
			$this->record = $this->dao->getRecordById($id);
			if (!$this->record['id']) {
				CoreServices::get('db')->transactionCommit();
				CoreUtils::redirect($this->getListPageAddress());
			}
		}
		else {
			CoreUtils::redirect(
				CoreServices2::getUrl()->createAddress('_m', 'Settings', '_o', 'CMSList')
			);
		}
		$this->initMultiselectRelations();
		$this->recordOldValues = $this->record; // clone!
		$this->checkUserPermissionsForRecord();
	}
	
	protected function initMultiselectRelations() {}
	
	protected function initActions() {
		$this->availableActions = array('Save');
	}

	protected function createFormFields() {
		parent::createFormFields();
		$this->form->addField(new CoreFormFieldText('settingName'));
		$this->form->addField(new CoreFormFieldCheckbox('settingState'));
	}

	protected function addFormValidators() {

	}

	protected function setSpecialRecordFieldsBeforeSave() {
		if (!empty($this->record['id'])) {
			if($this->record['settingName'] != $this->recordOldValues['settingName']) {
				$this->record['settingName'] = $this->recordOldValues['settingName'];
			}
		}
	}

	protected function afterSave() {}

	public function assignDisplayVariables() {
		parent::assignDisplayVariables();
		$display = CoreServices::get('display');
		$display->assign('record', $this->record);
	}

}
?>