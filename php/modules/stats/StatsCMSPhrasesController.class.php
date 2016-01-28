<?php

class StatsCMSPhrasesController extends CMSAbstractControllerList {
	protected function getDAOClass() {
		return 'PhraseDAO';
	}

	public function getMenuItemDescription() {
		return 'Stats';
	}

	protected function getFilterTypes() {
		return array(
			'phraseValue' => new CoreFilterLike(),
			'phraseCounter' => new CoreFilterMoreThanOrEqual()
		);
	}

	public function assignDisplayVariables() {
		parent::assignDisplayVariables();
		$display = CoreServices2::getDisplay();
		$display->assign('editButtonOff', 1);

	}

	// @TODO: o co tu chodzi?
	/*protected function initRecordList() {
		$columns = $this->getFilterTypes();
		$this->recordList = $this->dao->getFilteredPaginatedList(
			$columns,
			$this->filter,
			$this->pagination
		);
	}

	public function getRecordCount() {
		return $this->dao->getFilteredCountForStats($this->filter);
	}*/
}

?>