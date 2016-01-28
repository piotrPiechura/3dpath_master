<?php
class UserCMSListController extends CMSAbstractControllerList {
	protected function getDAOClass() {
		return 'UserDAO';
	}

	public function getMenuItemDescription() {
		return 'User';
	}

	protected function getFilterTypes() {
		return array(
			'userEmail' => new CoreFilterLike(),
			'userCompanyName' => new CoreFilterLike(),
			'userFirstName' => new CoreFilterLike(),
			'userSurname' => new CoreFilterLike(),
			'userNick' => new CoreFilterLike()
		);
	}

	public function assignDisplayVariables() {
		parent::assignDisplayVariables();
		CoreServices2::getDisplay()->assign('highlight', new UserCMSListHighlight());
	}
}
?>