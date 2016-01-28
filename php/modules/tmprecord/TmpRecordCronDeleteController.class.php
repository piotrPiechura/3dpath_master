<?php
class TmpRecordCronDeleteController implements iCoreController {
	protected $garbageCollector = null;

	public function prepareData() {
		if (!$this->isCLI()) {
			CoreUtils::redirect(CoreServices::get('url')->createAddress());
		}
		$this->garbageCollector = new TmpRecordGarbageCollector();
		try {
			$this->garbageCollector->clean();
		}
		catch (Exception $e) {
			$this->reportError($e->getMessage());
		}
	}

	protected function isCLI() {
		return !empty($_SERVER['argc']);
	}

	protected function reportError($message) {
		$from = CoreConfig::get('Environment', 'errorEmailSender');
		$listTo = array(CoreConfig::get('Environment', 'errorEmailRecipient'));
		$listCC = array();
		$lang = CoreConfig::get('CoreLangs', 'defaultLangCMS');
		$subject = CoreConfig::get('Environment', 'websiteName') . ' - ' . DictForCMS::get($lang, 'garbageCollectorFailure');
		$content =
			DictForCMS::get($lang, 'garbageCollectorFailure') . ":\n"
			. $message;
		CoreServices2::getMail()->sendPlainText($from, $listTo, $listCC, $subject, $content);
	}

	public function isTemplateEngineNeeded() {
		return False;
	}

	public function getSessionName() {
		return 'cronSession';
	}

	public function sendHeaders() {}

	public function display() {
		echo("OK\n");
	}
}
?>