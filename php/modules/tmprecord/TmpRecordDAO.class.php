<?php
class TmpRecordDAO extends CoreModelAbstractDAO {
	protected function getTableName() {
		return '_tmpRecord';
	}

	protected function getFields() {
		return array(
			'id' => null,
			'recordType' => null, // w zasadzie zbędne
			'_tmpRecordCreateTime' => null, // w zasadzie zbędne
			'_tmpRecordSessionId' => null
		);
	}

	protected function getManyToManyRelations()	{
		return array();
	}

	protected function hasNumerousDirectlyRelatedRecords(array &$record) {
		$fileDAO = new FileDAO();
		return $fileDAO->getCountByRecord('_tmpRecord', $record['id']) > 0;
	}

	protected function getDirectlyRelatedRecords(&$record) {
		$fileDAO = new FileDAO();
		return array(
			'FileDAO' => $fileDAO->getListByRecord('_tmpRecord', $record['id'])
		);
	}

	protected function getDefaultOrderBySQL() {
		return 'id';
	}

	public function getOldRecords($time, $limit) {
		$db = CoreServices2::getDB();
		$sql = '
			SELECT *
			FROM _tmpRecord
			WHERE _tmpRecordCreateTime < ' . $db->prepareInputValue($time) . '
			ORDER BY _tmpRecordCreateTime ASC
			LIMIT 0, ' . $db->prepareInputValue($limit);
		return $db->getRows($sql);
	}
}
?>
