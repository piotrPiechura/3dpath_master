<?php
abstract class AjaxAbstractController implements iCoreController {
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
		if (!$this->isControllerUsagePermitted()) {
			exit();
		}
	}

	abstract protected function isControllerUsagePermitted();

	public function isTemplateEngineNeeded() {
		return False;
	}

	public function sendHeaders() {
		header('Expires: Thu, 19 Nov 1981 08:52:00 GMT');
		header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		header('Pragma: no-cache');
		header('Content-type: text/plain; charset=' . strtolower(CoreConfig::get('CoreDisplay', 'globalCharset')));	
	}
}
?>