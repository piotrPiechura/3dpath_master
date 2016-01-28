<?php
class SubpageWebsiteController extends WebsiteAbstractControllerStandardLayout {
	protected $gallery = null;

	public function initSubpage() {
		$this->subpage = $this->subpageDAO->getRecordById(
			CoreServices2::getRequest()->getFromGet('id')
		);
		$modules = CoreServices2::getModules();
		if (
			$this->subpage['subpageModule'] != $modules->getControllerModule()
			|| $this->subpage['subpageMode'] != $modules->getControllerMode()
			|| $this->subpage['subpageState'] != 'visible'
		) {
			$this->layout->handleInvalidAddress();
		}
	}

	public function initAdditionalData() {
		parent::initAdditionalData();
		$fileDAO = new FileDAO();
		$this->gallery = $fileDAO->getListByRecord('subpage', $this->subpage['id'], 'image', 'gallery');
	}

	protected function updateStats() {
		parent::updateStats();
		$statsSimpleDAO = new StatsSimpleDAO();
		$statsSimpleDAO->increase('subpage', $this->subpage['id'], 'detailView');
	}

	public function assignDisplayVariables() {
		parent::assignDisplayVariables();
		$display = CoreServices2::getDisplay();
		if ($this->gallery) {
			$display->assign('gallery', $this->gallery);
		}
	}
}
?>