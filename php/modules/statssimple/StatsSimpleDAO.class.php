<?php
class StatsSimpleDAO extends CoreModelAbstractDAO {
	protected function getTableName() {
		return 'statsSimple';
	}
	
	protected function getFields() {
		return array(
			'id' => null,
			'recordType' => null,
			'recordId' => null,
			'statsSimpleName' => null,
			'statsSimpleValue' => null
		);
	}

	protected function hasNumerousDirectlyRelatedRecords(array &$record) {
		return False;
	}

	protected function getDirectlyRelatedRecords(&$record) {
		return array();
	}
	
	protected function getManyToManyRelations() {
		return array();
	}

	protected function getDefaultOrderBySQL() {
		return 'statsSimpleValue DESC';
	}

	public function save(&$record) {
		throw new CoreException('Method not implemented; use increase() instead!');
	}
	
	public function increase($recordType, $recordId, $statsName) {
		$db = CoreServices2::getDB();
		$sql = '
			UPDATE statsSimple
			SET statsSimpleValue = statsSimpleValue + 1
			WHERE
				recordType = ' . $db->prepareInputValue($recordType) . '
				AND recordId = ' . $db->prepareInputValue($recordId) . '
				AND statsSimpleName = ' . $db->prepareInputValue($statsName) . '
			LIMIT 1';
		$changedRows = $db->change($sql);
		if ($changedRows != 1) {
			$sql = '
				INSERT INTO statsSimple
				VALUES (
					NULL,
					' . $db->prepareInputValue($recordType) . ',
					' . $db->prepareInputValue($recordId) . ',
					' . $db->prepareInputValue($statsName) . ',
					1
				)';
			$db->insertRow($sql);
		}
	}

	public function delete(&$record) {
		throw new CoreException('Method not implemented; use clearStats() instead!');
	}

	public function clearStats($recordType, $statsSimpleName) {
		$db = CoreServices2::getDB();
		$sql = '
			DELETE FROM statsSimple
			WHERE
				recordType = ' . $db->prepareInputValue($recordType) . '
				AND statsSimpleName = ' . $db->prepareInputValue($statsSimpleName);
		$row = $db->change($sql);
	}

	public function getCount($recordType, $statsSimpleName) {
		$db = CoreServices2::getDB();
		$sql = '
			SELECT COUNT(*) AS num
			FROM statsSimple
			WHERE
				recordType = ' . $db->prepareInputValue($recordType) . '
				AND statsSimpleName = ' . $db->prepareInputValue($statsSimpleName);
		$row = $db->getRow($sql);
		return $row['num'];
	}

	protected function prepareRecordColumnList(&$recordColumns, $recordType) {
		if (
			array_key_exists('id', $recordColumns)
			|| array_key_exists($recordType . '.id', $recordColumns)
		) {
			throw new CoreException('Can\'t use column name \'id\'; use \'' . $recordType . 'Id\' instead!');
		}
		if (array_key_exists($recordType . 'Id', $recordColumns)) {
			unset($recordColumns[$recordType . 'Id']);
		}
	}

	public function getList($recordType, $statsSimpleName, &$recordColumns, $pagination, $order) {
		$recordColumnsModified = $recordColumns; // kopia! to ważne!
		$this->prepareRecordColumnList($recordColumnsModified, $recordType);
		$columnsSQL = $this->adaptColumnsSQL(
			implode(', ', array_keys($recordColumnsModified)) . ', statsSimpleValue'
		);
		$orderBySQL = $this->orderSQL($columns, $order);
		$offset = null;
		$limit = null;
		if ($pagination->getType() == 'Standard') {
			$offset = $pagination->getCurrentOffset();
			$limit = $pagination->getMaxRecordsOnPage();
		}
		$db = CoreServices2::getDB();
		$sql = '
			SELECT
				' . $recordType . '.id AS ' . $recordType . 'Id,
				' . $columnsSQL . '
			FROM statsSimple, ' . $recordType . '
			WHERE
				statsSimple.recordType = ' . $db->prepareInputValue($recordType) . '
				AND statsSimpleName = ' . $db->prepareInputValue($statsSimpleName) . '
				AND statsSimple.recordId = ' . $recordType . '.id
			ORDER BY ' . $orderBySQL . '
			' . $this->getLimitSQLClause($offset, $limit);
		return $db->getRows($sql);
	}
}
?>
