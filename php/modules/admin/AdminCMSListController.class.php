<?php
class AdminCMSListController extends CMSAbstractControllerList {
	protected function getDAOClass() {
		return 'AdminDAO';
	}

	public function getMenuItemDescription() {
		return 'Admin';
	}

	protected function isControllerUsagePermitted() {
		return (
			!empty($this->currentUser['id'])
			&& ($this->currentUser['adminRole'] >= $this->adminRoles['adminRoleDataAdmin'])
		);
	}

	protected function getFilterTypes() {
		return array(
			'adminName' => new CoreFilterLike(),
			'adminFirstName' => new CoreFilterLike(),
			'adminSurname' => new CoreFilterLike()
		);
	}
/*
	protected function getShownColumns() {
		return array(
			'adminName' => array('filter' => new ),
			'adminFirstName' => null,
			'adminSurname' => null
		);
	}
*/
	public function getRecordCount() {
		if ($this->currentUser['adminRole'] == $this->adminRoles['adminRoleSuperadmin']) {
			return parent::getRecordCount();
		}
		else {
			return 1;
		}
	}

	protected function initRecordList() {
		if ($this->currentUser['adminRole'] == $this->adminRoles['adminRoleSuperadmin']) {
			parent::initRecordList();
		}
		else {
			$this->recordList = array($this->dao->getRecordById($this->currentUser['id']));
		}
	}

	public function assignDisplayVariables() {
		parent::assignDisplayVariables();
		if ($this->currentUser['adminRole'] < $this->adminRoles['adminRoleSuperadmin']) {
			CoreServices::get('display')->assign('addButtonOff', 1);
			CoreServices::get('display')->assign('filterOff', 1);
		}
		CoreServices::get('display')->assign('highlight', new AdminCMSListHighlight());
		if ($this->currentUser['adminRole'] == $this->adminRoles['adminRoleSuperadmin']) {
			CoreServices::get('display')->assign('superadmin', true);
		} else {
			CoreServices::get('display')->assign('superadmin', false);
		}
	}
}
?>