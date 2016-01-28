<?php

class StatsCMSModelsController extends CMSAbstractControllerList {
	protected function getDAOClass() {
		return 'DownloadDAO';
	}

	public function getMenuItemDescription() {
		return 'Stats';
	}

	protected function getFilterTypes() {
		return array(
			'modelName' => new CoreFilterLike(),
			'authorName' => new CoreFilterLike(),
			'downloadStartTime' => new CoreFilterBetween('downloadStartTime', true, true)
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