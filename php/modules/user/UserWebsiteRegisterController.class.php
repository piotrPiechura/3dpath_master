<?php
class UserWebsiteRegisterController extends WebsiteAbstractControllerThickboxLayout {
	protected $dao = null;
	protected $record = null;
	protected $form = null;
	protected $errorMessageContainer = null;
	protected $redirectAddress = null;

	public function initAdditionalData() {
		parent::initAdditionalData();
		$this->dao = new UserDAO();
		if ($this->isUserLogged()) {
			$this->redirectToStep2();
		}
		$this->initRecord();
		$this->initForm();
		$this->createFormFields();
		if ($this->form->isSubmitted()) {
			$this->addFormValidators();
			$this->form->setFieldValuesFromRequest();
			$this->handleRequest();
			if (!empty($this->record['id'])) {
				$this->redirectToStep2();
			}
		}
	}

	protected function redirectToStep2() {
		$this->redirectToPage(
			CoreServices2::getUrl()->createAddress('_m', 'User', '_o', 'WebsiteRegister2'),
			'thickbox'
		);
	}

	protected function initRecord() {
		$this->record = $this->dao->getRecordTemplate();
	}

	protected function initForm() {
		$this->form = new CoreFormWithVLFs('post', null, 'mainForm');
	}

	protected function getRecordType() {
		return 'user';
	}

	protected function createFormFields() {
		$this->form->addField(new CoreFormFieldHidden('id'));
		$this->form->addField(new CoreFormFieldText('userEmail'));
		$this->form->addField(new CoreFormFieldText('userNick'));
		$this->form->addField(new CoreFormFieldPassword('userPassword'));
		$this->form->addField(new CoreFormFieldPassword('userPasswordConfirm'));
		$this->form->addField(new CoreFormFieldCheckbox('acceptRules'));
		$this->form->addField(new CoreFormFieldCheckbox('subscribeNewsletter'));
	}

	protected function addFormValidators() {
		$this->form->addValidator(new CoreFormValidatorNotEmpty('acceptRules'));
		$this->form->addValidator(new UserValidatorBasic($this->record));
	}

	protected function setFormValuesFromRecord() {
		$this->form->setFieldValuesFromRecord($this->record);
	}

	protected function setRecordValuesFromForm() {
		$this->form->setRecordValuesFromFields($this->record);
	}

	protected function subscribeNewsletter() {
		$newsletterSubscriberDAO = new NewsletterSubscriberDAO();
		$record = $newsletterSubscriberDAO->getByEmail($this->record['userEmail']);
		if ($record['newsletterSubscriberState'] != 'active') {
			if (empty($record['id'])) {
				$record['newsletterSubscriberEmail'] = $this->record['userEmail'];
			}
			$record['newsletterSubscriberRegistrationTime'] = CoreUtils::getDateTime();
			$record['newsletterSubscriberActivationCode'] = null;
			$record['newsletterSubscriberResignationCode'] =
				$newsletterSubscriberDAO->getNewResignationCode($record);
			$record['newsletterSubscriberState'] = 'active';
			$newsletterSubscriberDAO->save($record);
		}
	}

	// @TODO:
	// przetestować
	// ewentualnie zmienić na HTML ale skoro istock ma plaintextem...
	protected function sendConfirmationEmail() {
		$contentParams = array('userRecord' => $this->record);
		$contentObj = new UserConfirmationEmailContent($contentParams);
		$from = CoreConfig::get('Environment', 'registrationEmailSender');
		$listTo = array($this->record['userEmail']);
		$listCC = null;
		$subject = $contentObj->getSubject();
		$htmlContent = $contentObj->getContent();
		$attachments = $contentObj->getAttachments();
		// CoreServices2::getMail()->sendHTML($from, $listTo, $listCC, $subject, $htmlContent, $attachments);
		CoreServices2::getMail()->sendPlainText($from, $listTo, $listCC, $subject, $htmlContent, $attachments);
	}

	protected function handleRequest() {
		$this->errorMessageContainer = $this->form->getValidationResults();
		if (!$this->errorMessageContainer->isAnyErrorMessage()) {
			$this->setRecordValuesFromForm();
			$this->record['userRegisterTime'] = CoreUtils::getDateTime();
			$this->record['userState'] = 'active';
			$this->record['userCredits'] = 0;
			$this->dao->save($this->record);
			CoreServices2::getAccess()->login(
				$this->record['userEmail'],
				$this->record['userPassword']
			);
			if ($this->form->getField('subscribeNewsletter')->getValue()) {
				$this->subscribeNewsletter();
			}
			$this->sendConfirmationEmail();
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