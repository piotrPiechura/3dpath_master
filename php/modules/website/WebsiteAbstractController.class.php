<?php
abstract class WebsiteAbstractController extends CoreControllerAbstractPage {
	protected $currentUser = null;

	abstract protected function getBaseTemplate();

	/**
	 * Teoretycznie jest to odporne na thickboxy.
	 */
	protected function checkHTTPS() {
		$httpsOn = CoreServices2::getUrl()->isHTTPSOn();
		$httpsRequired = CoreConfig::get('Environment', 'httpsForWebsite');
		if ($httpsRequired && !$httpsOn) {
			CoreUtils::redirect(CoreServices2::getUrl()->getCurrentExactAddress('https'));
		}
		if (!$httpsRequired && $httpsOn) {
			CoreUtils::redirect(CoreServices2::getUrl()->getCurrentExactAddress('http'));
		}
	}

	public function prepareData() {
		$this->checkHTTPS();	
		$this->currentUser = CoreServices2::getAccess()->getCurrentUserData();
		if (!$this->isUsagePermitted()) {
			$this->redirectToPermissionDeniedPage();
		}
		$this->initAdditionalData();
		$this->updateStats();
	}

	abstract protected function initAdditionalData();

	protected function updateStats() {}
	
	protected function isUserLogged() {
		return ($this->currentUser['userState'] == 'active');
	}

	protected function isUsagePermitted() {
		return true;
	}

	public function getSessionName() {
		return 'WebsiteSession';
	}

	protected function setDisplayBasics() {
		CoreServices2::getDisplay()->setRootTemplateType($this->getBaseTemplate());
		CoreServices2::getDisplay()->setLang(CoreServices::get('lang')->getLang('Website'));
	}

	abstract protected function redirectToPage($url, $layoutType);

	protected function redirectToPermissionDeniedPage() {
		$this->redirectToHomePage();
	}

	protected function redirectToHomePage() {
		$this->redirectToPage(
			CoreServices2::getUrl()->createAddress(),
			'standard'
		);
	}

	public function sendHeaders() {
		parent::sendHeaders();
		header('Content-Language: ' . CoreServices::get('lang')->getLang('Website'));
	}
/*
	// @TODO: po grzyba komu ta idiotyczna funkcja???
	protected function checkCredits() {
		$creditsPackageDAO = new CreditsPackageDAO();
		$lastActiveCreditsPackage = $creditsPackageDAO->getLastActiveCreditsPackage(CoreServices2::getAccess()->getCurrentUserId());
		if(empty($lastActiveCreditsPackage['id'])) {
			$userDAO = new UserDAO();
			$user = $userDAO->getRecordById(CoreServices2::getAccess()->getCurrentUserId());
			$user['userCredits'] = 0;
			// @TODO: to juz jest po prostu szczyt! co to za bzdury???
			// to skutkuje czasami próba dodania rekordu do tabeli 'user'!!!
			$userDAO->save($user);
		}
	}
*/
	public function assignDisplayVariables() {
		parent::assignDisplayVariables();
		$display = CoreServices2::getDisplay();
		$display->assign('interfaceLang', CoreServices::get('lang')->getLang('Website'));
		if (!empty($this->currentUser['id'])) {
			$userName =
				!empty($this->currentUser['userFirstName']) && !empty($this->currentUser['userSurname'])
				? $this->currentUser['userFirstName'] . ' ' . $this->currentUser['userSurname']
				: $this->currentUser['userEmail'];
			$display->assign('userName', $userName);
			$display->assign('currentUserName', $userName);
                        $display->assign('userCredits', $this->currentUser['userCredits']);
		}
	}

}
?>