<?php
class SettingsCMSListController extends CMSAbstractControllerList {

	protected function getDAOClass() {
		return 'SettingsDAO';
	}

	public function getMenuItemDescription() {
		return 'Settings';
	}

	protected function getFilterTypes() {
		return array(
			'settingName' => null
		);
	}

	public function prepareData() {
		$this->checkHTTPS();
		$this->adminRoles = array_flip(CoreConfig::get('Data', 'adminRoles'));
		$this->currentUser = CoreServices::get('access')->getCurrentUserData();
		if (!$this->isControllerUsagePermitted()) {
			CoreUtils::redirect($this->getNoPermissionsAddress());
		}
		$this->initDAO();
		$this->initLayout();
		$this->initRecordList();
		$this->prepareAdditionalData();
	}

	protected function initRecordList() {
		$this->recordList = $this->dao->getListFull();
	}

	public function assignDisplayVariables() {
		parent::assignDisplayVariables();
		$display = CoreServices::get('display');
		$display->assign('highlight', new SettingsCMSListHighlight());
		$display->assign('addButtonOff', 1);
	}
}
?>