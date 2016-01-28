<?php

class StatsCMSCategoriesController extends CMSAbstractControllerList {
	protected function getDAOClass() {
		return 'ModelCategoryDAO';
	}

	public function getMenuItemDescription() {
		return 'Stats';
	}

	protected function getFilterTypes() {
		return array(
			'modelCategoryName' => new CoreFilterLike(),
			'count' => new CoreFilterNone()
		);
	}

	protected function initRecordList() {
		$this->recordList = $this->dao->getFilteredPaginatedListForStats(
			$this->filter,
			$this->pagination
		);
	}

	public function getRecordCount() {
		return $this->dao->getFilteredCountForStats($this->filter);
	}
}

?>