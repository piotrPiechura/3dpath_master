<?php
// @TODO: all
class UserWebsitePasswordRecovery2Controller extends WebsiteAbstractControllerStandardLayout {
	/**
	 * @var UserDAO
	 */
	protected $dao = null;
	protected $record = null;
	/**
	 * @var CoreForm
	 */
	protected $form = null;
	protected $errorMessageContainer = null;
	protected $successMessage = null;
	protected $redirectAddress = null;

	protected function initSubpage() {}

	protected function isUsagePermitted() {
		return empty($this->currentUser['id']);
	}

	public function prepareData() {
		parent::prepareData();
		if (CoreServices2::getRequest()->getFromGet('_sm')) {
			$this->successMessage = 1;
			return;
		}
		$this->dao = new UserDAO();
		$this->initRecord();
		$this->initForm();
		$this->createFormFields();
		if (empty($this->record['id'])) {
			// @TODO: własciwie w tym wypadku powinno sie przejść z powrotem do pierwszego
			//        formularza i rozpocząć całą procedurę od nowa
			$this->errorMessageContainer = new CoreFormValidationMessageContainer();
			$this->errorMessageContainer->addMessage('errorInvalidCode');
			return;
		}
		if ($this->form->isSubmitted()) {
			$this->addFormValidators();
			$this->form->setFieldValuesFromRequest();
			$this->handleRequest();
		}
		else {
			$this->setFormFieldValuesFromRecord();
		}
		if (!empty($this->redirectAddress)) {
			CoreUtils::redirect($this->redirectAddress);
		}
	}

	protected function initRecord() {
		$passwordChangeCode = CoreServices2::getRequest()->getFromRequest('cc');
		if (empty($passwordChangeCode)) {
			return;
		}
		$this->record = $this->dao->getActiveUserByPasswordChangeCode($passwordChangeCode);
	}

	protected function initForm() {
		$this->form = new CoreForm('post', null, 'mainForm');
	}

	protected function createFormFields() {
		$this->form->addField(new CoreFormFieldHidden('cc'));
		$this->form->addField(new CoreFormFieldPassword('userPassword'));
		$this->form->addField(new CoreFormFieldPassword('userPasswordConfirm'));
	}

	protected function addFormValidators() {
		$this->form->addValidator(new CoreFormValidatorNotEmpty('userPassword'));
		$this->form->addValidator(new CoreFormValidatorPasswordPattern('userPassword'));
		$this->form->addValidator(new CoreFormValidatorNotEmpty('userPasswordConfirm'));
		$this->form->addValidator(
			new CoreFormValidatorFieldsEqual(array('userPassword', 'userPasswordConfirm'))
		);
	}

	protected function setFormFieldValuesFromRecord() {
		$this->form->getField('cc')->setValue($this->record['userPasswordChangeCode']);
	}
	
	protected function handleRequest() {
		$this->errorMessageContainer = $this->form->getValidationResults();
		if (!$this->errorMessageContainer->isAnyErrorMessage()) {
			if ($this->record['id']) {
				$this->record['userPasswordChangeCode'] = null;
				$this->record['userPassword'] = $this->form->getField('userPassword')->getValue();
				$this->dao->save($this->record);
				CoreServices2::getAccess()->login(
					$this->record['userEmail'],
					$this->record['userPassword']
				);
				$this->redirectAddress = CoreServices2::getUrl()->getCurrentPageUrl(
					'_sm', 'Save',
					'id', $this->record['id']
				);
			}
		}
	}

	public function assignDisplayVariables() {
		parent::assignDisplayVariables();
		$display = CoreServices2::getDisplay();
		$display->assign('mainForm', $this->form);
		if (!is_null($this->errorMessageContainer) && $this->errorMessageContainer->isAnyErrorMessage()) {
			$display->assign('formErrorMessages', $this->errorMessageContainer);
		}
		if (!empty($this->successMessage)) {
			$display->assign('showSuccessMessage', 1);
		}
	}
}
?>