<?php

class StatsCMSTypesSearchController extends CMSAbstractControllerList {
	protected function getDAOClass() {
		return 'StatsSimpleDAO';
	}

	public function getMenuItemDescription() {
		return 'Stats';
	}

	protected function getFilterTypes() {
		return array(
			'modelTypeName' => new CoreFilterNone(),
			'statsSimpleValue' => new CoreFilterNone()
		);
	}

	protected function initRecordList() {
		$this->recordList = $this->dao->getList(
			'modelType',
			'search',
			$this->filter,
			$this->pagination,
			'statsSimpleValue DESC'
		);
	}

	public function getRecordCount() {
		return $this->dao->getCount('modelType', 'search');
	}

}

?>