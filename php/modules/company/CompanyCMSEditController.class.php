<?php
class CompanyCMSEditController extends CMSAbstractControllerEdit {
	public function getMenuItemDescription() {
		return 'Company';
	}

	protected function getDAOClass() {
		return 'CompanyDAO';
	}
	
	protected function initRecordManyToManyRelations() {}

	protected function initMultiselectRelations() {}
	
	/*protected function initActions() {
		$this->availableActions = array('Save');
		if ($this->record['faqItemState'] == 'visible') {
			$this->availableActions[] = 'Hide';
		} else {
			$this->availableActions[] = 'Show';
		}
		if(!empty($this->record['id']) && !$this->dao->hasRelatedRecords($this->record)) {
			$this->availableActions[] = 'Delete';
		}
	}*/

	protected function createFormFields() {
		parent::createFormFields();
		//$this->form->addField(new CoreFormFieldSelectAjax('Id'));
                
		$this->form->addField(new CoreFormFieldText('companyName'));
                $this->form->addField(new CoreFormFieldText('companyDivision'));
                $this->form->addField(new CoreFormFieldText('companyAddress'));
                $this->form->addField(new CoreFormFieldText('companyPhone'));
                $this->form->addField(new CoreFormFieldText('comapanyEmail'));
                $this->form->addField(new CoreFormFieldText('companySurveyCalcMethod'));
	}

	protected function addFormValidators() {
		//$this->form->addValidator(new UserValidatorActiveUserId());
		//$this->form->addValidator(new AuthorValidatorSingleAuthorForUserId());
		$this->form->addValidator(new CoreFormValidatorNotEmpty('companyName'));
		$this->form->addValidator(new CoreFormValidatorMaxTextLength('companyName', 100));
	}

	protected function setSpecialRecordFieldsBeforeSave() {
		if(empty($this->record['id'])) {
			$this->record['userId'] = $this->currentUser['id'];
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