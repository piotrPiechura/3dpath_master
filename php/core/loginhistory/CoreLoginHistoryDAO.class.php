<?php
class CoreLoginHistoryDAO extends CoreModelAbstractDAO {
	protected function getTableName() {
		return 'loginHistory';
	}

	protected function getFields() {
		return array(
			'id' => null,
			'userId' => null,
			'adminId' => null,
			'loginHistoryTime' => null,
			'loginHistoryMicrotime' => null,
			'loginHistoryIP' => null,
			'loginHistoryHostName' => null,
			'loginHistoryPHPSessionId' => null,
			'loginHistorySuccess' => null
		);
	}

	protected function getManyToManyRelations() {
		return array();
	}

	protected function hasNumerousDirectlyRelatedRecords(array &$record) {
		return False;
	}

	protected function getDirectlyRelatedRecords(&$record) {
		return array();
	}

	protected function getDefaultOrderBySQL() {
		return 'loginHistoryMicrotime DESC';
	}

	protected function getLastLogin($type, $id) {
		$db = CoreServices::get('db');
		$sql = '
			SELECT *
			FROM loginHistory
			WHERE
				' . $type . 'Id = ' . $db->prepareInputValue($id) . '
				AND loginHistorySuccess = 1
			ORDER BY
				loginHistoryMicrotime DESC
			LIMIT 0, 1';
		$rows = $db->getRows($sql);
		if (sizeof($rows) == 1) {
			return $rows[0];
		}
		return $this->getRecordTemplate();
	}

	public function getRecentFailedLoginAttempts($type, $id) {
		$lastLogin = $this->getLastLogin($type, $id);
		$db = CoreServices::get('db');
		$sql = '
			SELECT COUNT(*) AS num, MAX(loginHistoryTime) AS time
			FROM loginHistory
			WHERE
				' . $type . 'Id = ' . $db->prepareInputValue($id) . '
				AND (ISNULL(loginHistorySuccess) OR loginHistorySuccess = 0)';
		if ($lastLogin['loginHistoryMicrotime']) {
			$sql .= '
				AND loginHistoryMicrotime > ' . $db->prepareInputValue($lastLogin['loginHistoryMicrotime']);
		}
		return $db->getRow($sql);
	}
}
?>