<?php
class LogCMSListController extends CMSAbstractControllerList {

	protected function getDAOClass() {
		return 'LogDAO';
	}

	public function getMenuItemDescription() {
		return 'Logs';
	}

	protected function initPagination() {
		$this->pagination = new CorePaginationStandard(
			$this,
			CoreServices::get('url')->getCurrentPageFullUrl(),
			CoreConfig::get('Display', 'logsOnPage')
		);
	}

	protected function getFilterTypes() {
		$recordTypes = $this->dao->getDistinctList(array('recordType'), 'recordType_asc');
		$recordTypesOptions = $this->dao->modifyListForSelectBySpecificColumn(
			$recordTypes,
			'recordType',
			'<recordType>',
			'<any>'
		);
		$logOperations = $this->dao->getDistinctList(array('logOperation'), 'logOperation_asc');
		$logOperationsOptions = $this->dao->modifyListForSelectBySpecificColumn(
			$logOperations,
			'logOperation',
			'<logOperation>',
			'<any>'
		);
		return array(
			'adminName' => new CoreFilterLike(),
			'adminFirstName' => new CoreFilterLike(),
			'adminSurname' => new CoreFilterLike(),
			'recordType' => new CoreFilterSelect(true, $recordTypesOptions),
			'recordId' => new CoreFilterExact(),
			'logOperation' => new CoreFilterSelect(true, $logOperationsOptions),
			'logTime' => new CoreFilterBetween('logTime', true, true),
			'logIP' => new CoreFilterLike()
			// 'logDescription' => new CoreFilterNone()
		);
	}

	public function assignDisplayVariables() {
		parent::assignDisplayVariables();
		$display = CoreServices::get('display');
		$display->assign('addButtonOff', 1);
	}
}
?>