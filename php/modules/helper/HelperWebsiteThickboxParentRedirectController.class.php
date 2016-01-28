<?php
class HelperWebsiteThickboxParentRedirectController extends WebsiteAbstractControllerThickboxLayout {
	protected function isUsagePermitted() {
		return true;
	}

	protected function initLayout() {
		$this->layout = new WebsiteLayoutThickbox($this);
	}

	public function getSessionName() {
		return 'WebsiteSession';
	}

	public function assignDisplayVariables() {
		parent::assignDisplayVariables();
		$display = CoreServices2::getDisplay();
		$display->assign('newParentUrl', CoreServices2::getRequest()->getFromGet('url'));
	}
}
?>
