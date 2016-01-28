<?php
class ProjectCMSEditController extends CMSAbstractControllerEdit {
	public function getMenuItemDescription() {
		return 'Project';
	}

	protected function getDAOClass() {
		return 'ProjectDAO';
	}
	
	protected function initRecordManyToManyRelations() {}

	protected function initMultiselectRelations() {}
	
	protected function initActions() {
		$this->availableActions = array('Save');
		/*if ($this->record['faqItemState'] == 'visible') {
			$this->availableActions[] = 'Hide';
		} else {
			$this->availableActions[] = 'Show';
		}*/
		if(!empty($this->record['id']) && !$this->dao->hasRelatedRecords($this->record)) {
			$this->availableActions[] = 'Delete';
		}
	}
        
        protected function initForm() {
		$companyId = CoreServices2::getRequest()->getFromGet('comp');
		$this->form = new CoreForm(
			'post',
			CoreServices::get('url')->createHTML('_m', 'Project', '_o', 'CMSEdit', 'comp', $companyId)
		);
	}

	protected function createFormFields() {
		parent::createFormFields();
		//$this->form->addField(new CoreFormFieldSelectAjax('Id'));
                
		$this->form->addField(new CoreFormFieldText('projectName'));
                $this->form->addField(new CoreFormFieldText('projectLocation'));
                $this->form->addField(new CoreFormFieldText('projectDescription'));
                $this->form->addField(new CoreFormFieldText('projectSystem'));
                $this->form->addField(new CoreFormFieldText('projectElevation'));
                //$this->form->addField(new CoreFormFieldText('companySurveyCalcMethod'));
	}

	protected function addFormValidators() {
		//$this->form->addValidator(new UserValidatorActiveUserId());
		//$this->form->addValidator(new AuthorValidatorSingleAuthorForUserId());
		$this->form->addValidator(new CoreFormValidatorNotEmpty('projectName'));
		$this->form->addValidator(new CoreFormValidatorMaxTextLength('projectName', 100));
	}

	protected function setSpecialRecordFieldsBeforeSave() {
                $companyId = CoreServices2::getRequest()->getFromGet('comp');
		if(empty($this->record['id'])) {
			$this->record['companyId'] = $companyId;
		}
	}

	protected function afterSave() {}
        
        protected function handleDeleteRequest() {
		parent::handleDeleteRequest();
		$this->setRedirectAddress(CoreServices2::getUrl()->createAddress(
					'_m', 'Home',
					'_o', 'CMSEdit'
				));
		
	}

}
?>