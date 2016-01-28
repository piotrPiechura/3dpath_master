<?php
class UserWebsitePasswordRecovery1Controller extends WebsiteAbstractControllerThickboxLayout {
	protected $form = null;
	protected $redirectAddress = null;
	protected $errorMessageContainer = null;
	protected $successMessage = null;
	protected $userLogic = null;
	/**
	 * @var UserDAO
	 */
	protected $dao = null;

	public function prepareData() {
		parent::prepareData();
		$this->dao = new UserDAO();
		if (CoreServices::get('request')->getFromGet('_sm')) {
			$this->successMessage = 1;
			return;
		}
		$this->initForm();
		$this->createFormFields();
		if ($this->form->isSubmitted()) {
			$this->addFormValidators();
			$this->form->setFieldValuesFromRequest();
			$this->handleRequest();
		}
		if (!is_null($this->redirectAddress)) {
			CoreUtils::redirect($this->redirectAddress);
		}
	}

	// ok
	protected function isUsagePermitted() {
		return empty($this->currentUser['id']);
	}

	protected function initForm() {
		$this->form = new CoreForm('post', null, 'passwordRecoveryForm');
	}

	protected function createFormFields() {
		$this->form->addField(new CoreFormFieldText('userEmail'));
	}

	protected function addFormValidators() {
		$this->form->addValidator(new CoreFormValidatorNotEmpty('userEmail'));
	}

	protected function sendPasswordRecoveryEmail(&$record) {
		$params = array('userRecord' => $record);
		$contentObj = new UserPasswordRecoveryEmailContent($params);
		$from = CoreConfig::get('Environment', 'passwordRecoveryEmailSender');
		$listTo = array($record['userEmail']);
		$listCC = array(CoreConfig::get('Environment', 'errorEmailRecipient'));
		$subject = $contentObj->getSubject();
		$content = $contentObj->getContent();
		$attachments = $contentObj->getAttachments();
		// CoreServices2::getMail()->sendHTML($from, $listTo, $listCC, $subject, $content, $attachments);
		CoreServices2::getMail()->sendPlainText($from, $listTo, $listCC, $subject, $content, $attachments);
	}

	protected function handleRequest() {
		$this->errorMessageContainer = $this->form->getValidationResults();
		if (!$this->errorMessageContainer->isAnyErrorMessage()) {
			$userEmail = $this->form->getField('userEmail')->getValue();
			$record = $this->dao->getActiveUserByEmail($userEmail);
			if ($record['userState'] != 'active') {
				$this->errorMessageContainer->addMessage('errorInvalidUserEmail');
			}
			else {
				$record['userPasswordChangeCode'] = $this->dao->getNewPasswordChangeCode($record);
				$this->dao->save($record);
				$this->sendPasswordRecoveryEmail($record);
				$this->redirectAddress = CoreServices2::getUrl()->getCurrentPageUrl('_sm', 'SendLink');
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