<?php
class LogDAO extends CoreModelAbstractDAO {
	protected function getTableName() {
		return 'log';
	}

	protected function getFields() {
		return array(
			'id' => null,
			'adminId' => null,
			'recordType' => null,
			'recordId' => null,
			'logOperation' => null,
			'logTime' => null,
			'logIP' => null,
			'logDescription' => null
		);
	}

	protected function getManyToManyRelations()	{
		return array();
	}

	protected function hasNumerousDirectlyRelatedRecords(array &$record) {
		return False;
	}

	protected function getDirectlyRelatedRecords(&$record) {
		return array();
	}

	protected function getDefaultOrderBySQL() {
		return 'logTime DESC';
	}

	public function getListFull() {
		return $this->getListBySQLParams(
			implode(', ', array_keys($this->getFields())),
			null,
			$this->getDefaultOrderBySQL()
		);
	}

	public function getFilteredPaginatedList(&$columns, &$filter, $pagination, $order = null) {
		$db = CoreServices::get('db');
		$columnsSQL = $columns ? implode(', ', array_keys($columns)) : null;
		$columnsSQL = $this->adaptColumnsSQL($columnsSQL);
		$whereSQL = $this->whereSQLForFilter($filter);
		$orderBySQL = $this->orderSQL($columns, $order);
		$offset = null;
		$limit = null;
		if ($pagination->getType() == 'Standard') {
			$offset = $pagination->getCurrentOffset();
			$limit = $pagination->getMaxRecordsOnPage();
		}

		$sql = '
			SELECT ' . $columnsSQL . '
			FROM log, admin
			WHERE ' . $whereSQL . '
				AND adminId = admin.id
			ORDER BY ' . $orderBySQL;
		if ($limit) {
			if (!$offset) {
				$offset = '0';
			}
			$sql .= ' LIMIT ' . $offset . ', ' . $limit;
		}
		$rows = $db->getRows($sql);
		return $rows;
	}

	public function getFilteredCount(&$filter) {
		$db = CoreServices::get('db');
		$whereSQL = $this->whereSQLForFilter($filter);
		$sql = '
			SELECT COUNT(*) AS num
			FROM log, admin
			WHERE ' . $whereSQL . '
				AND adminId = admin.id';
		$row = $db->getRow($sql);
		return $row['num'];
	}

}
?>
