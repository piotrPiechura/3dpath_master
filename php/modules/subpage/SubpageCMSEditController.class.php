<?php
class SubpageCMSEditController extends CMSAbstractControllerEditWithFileUpload {
	protected function getDAOClass() {
		return 'SubpageDAO';
	}

	public function getMenuItemDescription() {
		return 'Subpage';
	}

	protected function isControllerUsagePermitted() {
		return (
			!empty($this->currentUser['id'])
			&& $this->currentUser['adminRole'] >= $this->adminRoles['adminRoleSuperadmin']
		);
	}

	protected function initRecord() {
		parent::initRecord();
		if (
			empty($this->record['id'])
			|| $this->record['subpageModule'] != 'Subpage'
			|| $this->record['subpageMode'] != 'Website'
		) {
			CoreUtils::redirect($this->getListPageAddress());
		}
	}

	protected function initMultiselectRelations() {}

//	protected function getHandledFileLists() {
//		return array(
//			'gallery' => 'image'
//		);
//	}
	
	protected function initActions() {
		$this->availableActions = array();
		$this->availableActions[] = 'Save';
		// @TODO: ukrywanie i pokazywanie podstron
	}

	// @TODO:
//	protected function createMenuField() {
//		$this->form->addField(new CoreFormFieldSelect(
//			'menuId',
//			null,
//			array(0 => '<choose>') + CoreConfig::get('Structure', 'websiteMenus')
//		));
//	}

	protected function createFormFields() {
		parent::createFormFields();
//		$this->createMenuField();
		$this->form->addField(new CoreFormFieldCheckbox('subpageLoginRequired'));
		$this->form->addField(new CoreFormFieldText('subpageCaption'));
		$this->form->addField(new CoreFormFieldText('subpageLead'));
		$this->form->addField(new CoreFormFieldText('subpageTitle'));
		$this->form->addField(new CoreFormFieldHTML('subpageContent'));
	}

	protected function addFormValidators() {
		parent::addFormValidators();
//		$this->form->addValidator(new CoreFormValidatorNotEmpty('menuId'));
		$this->form->addValidator(new CoreFormValidatorNotEmpty('subpageCaption'));
		$this->form->addValidator(new CoreFormValidatorMaxTextLength('subpageCaption', 32));
		$this->form->addValidator(new CoreFormValidatorMaxTextLength('subpageLead', 180));
		$this->form->addValidator(new CoreFormValidatorNotEmpty('subpageTitle'));
		$this->form->addValidator(new CoreFormValidatorMaxTextLength('subpageTitle', 80));
		$this->form->addValidator(new CoreFormValidatorMaxTextLength('subpageContent', 10000));

	}
}
?>