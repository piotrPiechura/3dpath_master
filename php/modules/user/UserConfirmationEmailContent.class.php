<?php
class UserConfirmationEmailContent extends MailAbstractTextContent {
	protected function prepareData() {
		CoreUtils::checkConstraint(!empty($this->params['userRecord']['userEmail']));
	}

	protected function initLang() {
		$this->lang = CoreConfig::get('CoreLangs', 'defaultLangWebsite');
	}
	
	protected function assignDisplayVariables() {
		parent::assignDisplayVariables();
		$this->display->assign('userEmail', $this->params['userRecord']['userEmail']);
	}
}
?>