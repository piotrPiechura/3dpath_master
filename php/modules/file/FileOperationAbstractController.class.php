<?php
abstract class FileOperationAbstractController extends CoreControllerAbstractPage {
	public function isTemplateEngineNeeded() {
		return False;
	}

	protected function getViewParam() {
		$view =	CoreServices2::getRequest()->getFromPost('view');
		if (empty($view)) {
			$view =	CoreServices2::getRequest()->getFromGet('view');
		}
		return $view;
	}

	public function getSessionName() {
		$view = $this->getViewParam();
		switch ($view) {
			case 'c':
				return 'CMSSession';
			case 'w':
				return 'WebsiteSession';
		}
		return null;
	}

	protected function setDisplayContentType() {}

	protected function getNoPermissionsAddress() {}

	protected function setDisplayBasics() {}

	protected function checkHTTPS() {
		$httpsOn = CoreServices2::getUrl()->isHTTPSOn();
		if ($this->getSessionName() == 'CMSSession') {
			$httpsRequired = CoreConfig::get('Environment', 'httpsForCMS');
		}
		elseif ($this->getSessionName() == 'WebsiteSession') {
			$httpsRequired = CoreConfig::get('Environment', 'httpsForWebsite');
		}
		else {
			$httpsRequired = False; // i tak nie ma sesji!
		}
		if ($httpsRequired && !$httpsOn) {
			CoreUtils::redirect(CoreServices::get('url')->getCurrentExactAddress('https'));
		}
		if (!$httpsRequired && $httpsOn) {
			CoreUtils::redirect(CoreServices::get('url')->getCurrentExactAddress('http'));
		}
	}

	public function prepareData() {
		$this->checkHTTPS();
	}

	public function assignDisplayVariables() {}

	protected function displayOK() {}

	protected function displayError() {
		$allMessages = $this->messageManager->getMessages();
		list($index, $firstError) = each($allMessages);
		$firstMessage =
			(!empty($firstError['messages'][0]))
			? $firstError['messages'][0]
			: 'unknownError';
		$returnValue = json_encode(array(
			'status' => 'ERROR',
			'message' => $firstMessage
		));
		echo($returnValue);
	}

	public function display() {
		if (!$this->messageManager->isAnyErrorMessage()) {
			$this->displayOK();
		}
		else {
			$this->displayError();
		}
	}

}
?>
