<?php
class UserWebsiteDeleteController extends WebsiteAbstractControllerThickboxLayout {
	protected $dao = null;
	protected $record = null;
	protected $form = null;
	protected $errorMessageContainer = null;
	protected $redirectAddress = null;

	public function initAdditionalData() {
		parent::initAdditionalData();
		$this->dao = new UserDAO();
		$this->initRecord();
		$this->initForm();
		$this->createFormFields();
		if ($this->form->isSubmitted()) {
			$this->addFormValidators();
			$this->form->setFieldValuesFromRequest();
			$this->handleRequest();
		}
	}

	protected function redirectToStep2() {
		$this->redirectToPage(
			CoreServices2::getUrl()->createAddress('_m', 'User', '_o', 'WebsiteDelete2'),
			'thickbox'
		);
	}

	protected function isUsagePermitted() {
		return $this->isUserLogged();
	}

	protected function initRecord() {
		$this->record = $this->dao->getRecordById($this->currentUser['id']);
	}

	protected function initForm() {
		$this->form = new CoreForm('post', null, 'mainForm');
	}

	protected function createFormFields() {
		$this->form->addField(new CoreFormFieldHidden('id'));
		$this->form->getField('id')->setValue($this->currentUser['id']);
	}

	protected function addFormValidators() {
		//$this->form->addValidator(new CoreFormValidatorNotEmpty('acceptRules'));
	}

	protected function setFormValuesFromRecord() {
		$this->form->setFieldValuesFromRecord($this->record);
	}

	protected function setRecordValuesFromForm() {
		$this->form->setRecordValuesFromFields($this->record);
	}

	protected function handleRequest() {
		$this->errorMessageContainer = $this->form->getValidationResults();
		if (!$this->errorMessageContainer->isAnyErrorMessage()) {
			$this->setRecordValuesFromForm();
			$this->record['userEraseRequestTime'] = CoreUtils::getDateTime();
			$this->record['userState'] = 'forDeletion';
			$this->dao->save($this->record);
			CoreServices2::getAccess()->logout();
			$this->redirectToStep2();
		}
	}

	public function assignDisplayVariables() {
		parent::assignDisplayVariables();
		$display = CoreServices2::getDisplay();
		$display->assign('mainForm', $this->form);
		if (
			!is_null($this->errorMessageContainer)
			&& $this->errorMessageContainer->isAnyErrorMessage()
		) {
			$display->assign('formErrorMessages', $this->errorMessageContainer);
		}
	}
}
?>