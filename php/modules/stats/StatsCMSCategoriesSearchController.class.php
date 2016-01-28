<?php

class StatsCMSCategoriesSearchController extends CMSAbstractControllerList {
	protected function getDAOClass() {
		return 'StatsSimpleDAO';
	}

	public function getMenuItemDescription() {
		return 'Stats';
	}

	protected function getFilterTypes() {
		return array(
			'modelCategoryName' => new CoreFilterNone(),
			'statsSimpleValue' => new CoreFilterNone()
		);
	}

	protected function initRecordList() {
		$this->recordList = $this->dao->getList(
			'modelCategory',
			'search',
			$this->filter,
			$this->pagination,
			'statsSimpleValue DESC'
		);
	}

	public function getRecordCount() {
		return $this->dao->getCount('modelCategory', 'search');
	}

}

?>