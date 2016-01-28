<?php

class StatsCMSUsersController extends CMSAbstractControllerList {
	protected function getDAOClass() {
		return 'UserDAO';
	}

	public function getMenuItemDescription() {
		return 'Stats';
	}

	protected function getFilterTypes() {
		return array(
			'userEmail' => new CoreFilterLike(),
			'userCompanyName' => new CoreFilterLike(),
			'userFirstName' => new CoreFilterLike(),
			'userSurname' => new CoreFilterLike(),
			'userCredits' => new CoreFilterLike()
		);
	}

	protected function initRecordList() {
		$columns = $this->getFilterTypes();
		$this->recordList = $this->dao->getFilteredPaginatedListForStats(
			$columns,
			$this->filter,
			$this->pagination
		);
	}

	public function getRecordCount() {
		return $this->dao->getFilteredCountForStats($this->filter);
	}

}

?>