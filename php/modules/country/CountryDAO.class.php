<?php
class CountryDAO extends CoreModelAbstractDAO {
	protected function getTableName() {
		return 'country';
	}

	protected function getFields() {
		return array(
			'id' => null,
			'countryName' => null,
			'countryNameOptima' => null,
			'countryAbbr' => null,
			'countryInvoiceGroup' => null
		);
	}

	protected function getManyToManyRelations()	{
		return array();
	}

	protected function hasNumerousDirectlyRelatedRecords(array &$record) {
		$userDAO = new UserDAO();
		return $userDAO->getCountByForeignKey('countryId', $record['id']) > 0;
	}

	protected function getDirectlyRelatedRecords(&$record) {
		$userDAO = new UserDAO();
		return array(
			'UserDAO' => $userDAO->getListByForeignKey('countryId', $record['id'])
		);
	}

	protected function getDefaultOrderBySQL() {
		return 'countryName ASC';
	}

	public function getSimpleList() {
		return parent::getListBySQLParams('id, countryName');
	}

	public function getRecordByAbbr($abbr) {
		$db = CoreServices2::getDB();
		$sql = '
			SELECT *
			FROM country
			WHERE countryAbbr = ' . $db->prepareInputValue(strtoupper($abbr)) . '
			ORDER BY id
			LIMIT 0, 1';
		$rows = $db->getRows($sql);
		if (sizeof($rows) == 1) {
			return $rows[0];
		}
		return $this->getRecordTemplate();
	}
}
?>
