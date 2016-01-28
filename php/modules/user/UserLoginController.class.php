<?php
class UserLoginController extends WebsiteAbstractControllerThickboxLayout {
	protected $loginForm = null;
	/**
	 * @var CoreFormValidationMessageContainer
	 */
	protected $loginErrorMessageContainer = null;

	public function initAdditionalData() {
		parent::initAdditionalData();
		$this->handleLoginForm();
	}

	protected function loginAndReload() {
		$success = CoreServices2::getAccess()->login(
			$this->loginForm->getField('userEmail')->getValue(),
			$this->loginForm->getField('userPassword')->getValue(),
			$this->loginErrorMessageContainer
		);
		if ($success) {
			$goBackAddress = CoreServices2::getRequest()->getFromGet('goBack');
			if (empty($goBackAddress)) {
				$goBackAddress = CoreServices2::getUrl()->createAddress();
			}
			// @TODO: to lekko ryzykowne zakładać że przekierowanie może być tylko
			// ze standardowego widoku
			$this->redirectToPage($goBackAddress, 'standard');
		}
	}

	protected function handleLoginForm() {
		if (!empty($this->currentUser)) {
			// jeżeli ktoś jest już zalogowany a mimo to trafił na stronę logowania,
			// to być może chciał na przykład oglądać cudze dane. W takim przypadku
			// nie można go przekierować tam skąd przyszedł bo prawdopodobnie spowoduje
			// to zapętlenie przekierowań. Bezpiecznie i sensownie jest skierować
			// delikwenta na stronę główną.
			$this->redirectToHomePage();
		}
		else {
			$this->loginForm = new CoreForm(
				'post',
				CoreServices2::getUrl()->getCurrentExactAddress(),
				'loginForm'
			);
			$this->createLoginFormFields();
			if ($this->loginForm->isSubmitted()) {
				$this->addLoginFormValidators();
				CoreServices2::getAccess()->logout();
				$this->loginForm->setFieldValuesFromRequest();
				$this->loginErrorMessageContainer = $this->loginForm->getValidationResults();
				if (!$this->loginErrorMessageContainer->isAnyErrorMessage()) {
					$this->loginAndReload();
				}
			}
		}
	}

	protected function createLoginFormFields() {
		$this->loginForm->addField(new CoreFormFieldText('userEmail'));
		$this->loginForm->addField(new CoreFormFieldPassword('userPassword'));
		$this->loginForm->addField(new CoreFormFieldSubmit('_login'));
	}

	protected function addLoginFormValidators() {
		$this->loginForm->addValidator(new CoreFormValidatorNotEmpty('userEmail'));
		$this->loginForm->addValidator(new CoreFormValidatorMaxTextLength('userEmail', 40));
		$this->loginForm->addValidator(new CoreFormValidatorNotEmpty('userPassword'));
		$this->loginForm->addValidator(new CoreFormValidatorMaxTextLength('userPassword', 40));
	}

	protected function assignErrorValue() {
		$error = null;
		if (
			!empty($this->loginErrorMessageContainer)
			&& $this->loginErrorMessageContainer->isAnyErrorMessage()
		) {
			$messages = $this->loginErrorMessageContainer->getMessages();
			if (
				!empty($messages['']['messages'][0])
				&& $messages['']['messages'][0] == 'userAccountTemporarilyBlocked'
			) {
				$error = 'userAccountTemporarilyBlocked';
			}
			else {
				$error = 'defaultLoginErrorMessage';
			}
		}
		elseif (CoreServices2::getRequest()->getFromGet('error')) {
			$error = 'defaultLoginErrorMessage';
		}
		CoreServices2::getDisplay()->assign('error', $error);
	}

	public function assignDisplayVariables() {
		parent::assignDisplayVariables();
		$display = CoreServices2::getDisplay();
		$display->assign('loginForm', $this->loginForm);
		$this->assignErrorValue();
	}
}
?>