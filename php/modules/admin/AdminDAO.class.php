<?php
class AdminDAO extends CoreModelAbstractDAO {
	protected function getTableName() {
		return 'admin';
	}

	protected function getFields() {
		return array(
			'id' => null,
			'adminState' => null,
			'adminName' => null,
			'adminPassword' => null,
			'adminFirstName' => null,
			'adminSurname' => null,
			'adminRole' => null
		);
	}

	public function isRegisteredAdmin($adminName) {
		$row = CoreServices::get('db')->getRow($this->registeredAdminSQL($adminName));
		return $row['num'];
	}

	protected function registeredAdminSQL($adminName) {
		return '
			SELECT COUNT(*) AS num
			FROM ' . $this->getTableName() . '
			WHERE adminName = ' . CoreServices::get('db')->prepareInputValue($adminName);
	}

	protected function getManyToManyRelations()	{
		return array();
	}

	protected function hasNumerousDirectlyRelatedRecords(array &$record) {
		//$forumThreadDAO = new ForumThreadDAO();
		//$forumPostDAO = new ForumPostDAO();
		//return
			//$forumThreadDAO->getCountByForeignKey('adminId', $record['id']) > 0
			//|| $forumPostDAO->getListByForeignKey('adminId', $record['id']) > 0;
	}

	protected function getDirectlyRelatedRecords(&$record) {
		//$forumThreadDAO = new ForumThreadDAO();
		//$forumPostDAO = new ForumPostDAO();
		return array(
			// forum
			//'ForumThreadDAO' => $forumThreadDAO->getListByForeignKey('adminId', $record['id']),
			//'ForumPostDAO' => $forumPostDAO->getListByForeignKey('adminId', $record['id'])
		);
	}

	public function getByNameAndPassword($adminName, $password) {
		$db = CoreServices::get('db');
		$sql = '
			SELECT *
			FROM admin
			WHERE
				adminName = ' . $db->prepareInputValue($adminName) . '
				AND adminPassword = ' . $db->prepareInputValue($password) . '
				AND adminState = \'active\'';
		$rows = $db->getRows($sql);
		if (!empty($rows) && sizeof($rows) == 1) {
			return $rows[0];
		}
		return $this->getRecordTemplate();
	}

	public function getByName($adminName) {
		$db = CoreServices::get('db');
		$sql = '
			SELECT *
			FROM admin
			WHERE
				adminName = ' . $db->prepareInputValue($adminName) . '
				AND adminState = \'active\'';
		$rows = $db->getRows($sql);
		if (!empty($rows) && sizeof($rows) == 1) {
			return $rows[0];
		}
		return $this->getRecordTemplate();
	}

	protected function getDefaultOrderBySQL() {
		return 'adminName';
	}

	public function getFilteredPaginatedList(&$columns, &$filter, $pagination, $order = null) {
		$cols = array('adminState' => null) + $columns;
		return parent::getFilteredPaginatedList($cols, $filter, $pagination);
	}
        
        public function getRecordPath($recordId){ 
		return 0;
        }
}
?>
