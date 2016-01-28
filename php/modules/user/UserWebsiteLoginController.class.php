<?php
class UserWebsiteLoginController extends WebsiteAbstractController {
	protected function isUsagePermitted() {
		return True;
	}

	public function getSessionName() {
		return 'WebsiteSession';
	}

	protected function initLayout() {
		$this->layout = new WebsiteLayoutStandard($this);
	}

	protected function initLoginForm() {
		if (!empty($this->currentUser)) {
			// jeżeli ktoś jest już zalogowany a mimo to trafił na stronę logowania,
			// to być może chciał na przykład oglądać cudze dane. W takim przypadku
			// nie można go przekierowac tam skąd przyszedł bo prawdopodobnie spowoduje
			// to zapętlenie przekierowań. Bezpiecznie i sensownie jest skierować
			// delikwenta na stronę główną.
			$this->layout->redirectToHomePage();
		}
		else {
			$this->loginForm = new CoreForm(
				'post',
				CoreServices::get('url')->getCurrentExactAddress()
			);
			$this->createLoginFormFields();
			if ($this->loginForm->isSubmitted()) {
				$this->addLoginFormValidators();
				CoreServices::get('access')->logout();	
				$this->loginForm->setFieldValuesFromRequest();
				$this->loginErrorMessageContainer = $this->loginForm->getValidationResults();
				if (!$this->loginErrorMessageContainer->isAnyErrorMessage()) {
					$success = CoreServices::get('access')->login(
						$this->loginForm->getField('userEmail')->getValue(),
						$this->loginForm->getField('userPassword')->getValue(),
						$this->loginErrorMessageContainer
					);
					if ($success) {
						/*
						// @TODO: na co to komu potrzebne?
						$this->checkCredits();
						 */
						// $goBackAddress = CoreServices::get('request')->getFromGet('goBack');
						// if (empty($goBackAddress)) {
						//	$goBackAddress = CoreServices::get('url')->createAddress('_m', 'Home', '_o', 'Website');
						//}
						// CoreUtils::redirect($goBackAddress);
						$this->layout->redirectToHomePage();
					}
				}
			}
		}
	}

	protected function addLoginFormValidators() {
		$this->loginForm->addValidator(new CoreFormValidatorNotEmpty('userEmail'));
		$this->loginForm->addValidator(new CoreFormValidatorMaxTextLength('userEmail', 40));
		$this->loginForm->addValidator(new CoreFormValidatorNotEmpty('userPassword'));
		$this->loginForm->addValidator(new CoreFormValidatorMaxTextLength('userPassword', 40));
	}

	public function assignDisplayVariables() {
		parent::assignDisplayVariables();
		$display = CoreServices::get('display');
		if (!empty($this->loginErrorMessageContainer) && $this->loginErrorMessageContainer->isAnyErrorMessage()) {
			CoreServices::get('display')->assign('loginErrorMessages', $this->loginErrorMessageContainer);
		}
	}
}
?>