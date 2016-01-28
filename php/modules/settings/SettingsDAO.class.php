<?php
class SettingsDAO extends CoreModelAbstractDAO {
	protected function getTableName() {
		return 'settings';
	}

	protected function getFields() {
		return array(
			'id' => null,
			'settingName' => null,
			'settingState' => null
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
		return 'settingName ASC';
	}

	public function getListFull() {
		return $this->getListBySQLParams(
			implode(', ', array_keys($this->getFields())),
			null,
			$this->getDefaultOrderBySQL()
		);
	}

	public function getByName($name) {
		$db = CoreServices::get('db');
		$sql = '
			SELECT *
			FROM settings
			WHERE
				settingName = ' . $db->prepareInputValue($name);
		$setting = $db->getRow($sql);
		return $setting;
	}

	public function getState($name) {
		$db = CoreServices::get('db');
		$sql = '
			SELECT settingState
			FROM settings
			WHERE
				settingName = ' . $db->prepareInputValue($name);
		$setting = $db->getRow($sql);
		return $setting['settingState'];
	}

}
?>