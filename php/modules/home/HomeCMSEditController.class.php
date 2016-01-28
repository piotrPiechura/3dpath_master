<?php
class HomeCMSEditController extends CMSAbstractControllerEditWithFileUpload {
	protected function getDAOClass() {
		return 'HomeDAO';
	}

	public function getMenuItemDescription() {
		return 'Home';
	}

	protected function initRecord() {
		$this->record = $this->dao->getRecord();
	}

	protected function initMultiselectRelations() {}

	protected function getHandledFileLists() {
		return array(
			'box1Image' => 'image',
			'box2Image' => 'image'
		);
	}

	protected function initActions() {
		//$this->availableActions = array('Save');
	}

	protected function createFormFields() {
		/*parent::createFormFields();
		$this->form->addField(new CoreFormFieldText('homeBox1Title'));
		$this->form->addField(new CoreFormFieldText('homeBox1Subtitle'));
		$this->form->addField(new CoreFormFieldHTML('homeBox1Content'));
		$this->form->addField(new CoreFormFieldText('homeBox2Title'));
		$this->form->addField(new CoreFormFieldText('homeBox2Subtitle'));
		$this->form->addField(new CoreFormFieldText('homeBox2Link'));*/
	}

        protected function getMenuData() {
          
        }
        
        protected function prepareAdditionalData() {
            parent::prepareAdditionalData();
            if (!empty($this->currentUser['id'])) {
			if (CoreServices2::getRequest()->getFromGet('logout') == 1) {
				CoreServices2::getAccess()->logout();
				$this->redirectToHomePage();
			}
		}
        }
        
	protected function addFormValidators() {
		/*parent::addFormValidators();

		$this->form->addValidator(new CoreFormValidatorNotEmpty('homeBox1Title'));
		$this->form->addValidator(new CoreFormValidatorMaxTextLength('homeBox1Title', 30));
		$this->form->addValidator(new CoreFormValidatorMaxTextLength('homeBox1Subtitle', 30));

		$this->form->addValidator(new CoreFormValidatorNotEmpty('homeBox1Content'));
		$this->form->addValidator(new CoreFormValidatorMaxTextLength('homeBox1Content', 450));
	
		$this->form->addValidator(new CoreFormValidatorNotEmpty('homeBox2Title'));
		$this->form->addValidator(new CoreFormValidatorMaxTextLength('homeBox2Title', 30));
		$this->form->addValidator(new CoreFormValidatorMaxTextLength('homeBox2Subtitle', 30));
		$this->form->addValidator(new CoreFormValidatorMaxTextLength('homeBox2Link', 200));		
		$this->form->addValidator(new CoreFormValidatorWWWPageFullUrl('homeBox2Link'));*/
 	}
}
?>