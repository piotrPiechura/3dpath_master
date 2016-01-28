<?php
class UserDAO extends CoreModelAbstractDAO {
	protected function getTableName() {
		return 'user';
	}

	protected function getFields() {
		return array(
			'id' => null,
			'userState' => null,
			'userEmail' => null,
			'userPassword' => null,
			'userPasswordChangeCode' => null,
			'userRegisterTime' => null,
			'userEraseRequestTime' => null,
			'userEraseTime' => null,
			'userNick' => null,
			'userSignature' => null,
			'userCompanyName' => null,
			'userFirstName' => null,
			'userSurname' => null,
			'userAddressStreet' => null,
			'userAddressCity' => null,
			'userAddressRegion' => null,
			'userZipCode' => null,
			'countryId' => null,
			'userTaxIdentifier' => null,
			'userCredits' => null
		);
	}

	public function isActiveUser($userEmail) {
		$sql = '
			SELECT COUNT(*) AS num
			FROM user
			WHERE
				userEmail = ' . CoreServices::get('db')->prepareInputValue($userEmail) . '
				AND userState = \'active\'';
		$row = CoreServices::get('db')->getRow($sql);
		return $row['num'];
	}

	public function getActiveUserByPasswordChangeCode($passwordChangeCode) {
		$db = CoreServices2::getDB();
		$sql = '
			SELECT *
			FROM user
			WHERE
				userPasswordChangeCode =
					' . $db->prepareInputValue($passwordChangeCode) . '
				AND userState = \'active\'';
		$rows = $db->getRows($sql);
		if (sizeof($rows) == 1) {
			return $rows[0];
		}
		return $this->getRecordTemplate();
	}

	// @TODO: potrzebne to?
	public function getActiveUserByEmail($userEmail) {
		$sql = '
			SELECT *
			FROM user
			WHERE
				userEmail = ' . CoreServices::get('db')->prepareInputValue($userEmail) . '
				AND userState = \'active\'';
		$rows = CoreServices::get('db')->getRows($sql);
		if (sizeof($rows) == 1) {
			return $rows[0];
		}
		return $this->getRecordTemplate();
	}

	public function isRegisteredUser($userEmail) {
		$db = CoreServices::get('db');
		$sql = '
			SELECT COUNT(*) AS num
			FROM ' . $this->getTableName() . '
			WHERE
				userEmail = ' . $db->prepareInputValue($userEmail) . '
				AND userState != \'deleted\'';
		$row = $db->getRow($sql);
		return $row['num'];
	}

	protected function getManyToManyRelations()	{
		return array(
			'forumThread' => null
		);
	}

	protected function hasNumerousDirectlyRelatedRecords(array &$record) {
		$creditsPackageDAO = new CreditsPackageDAO();
		$downloadDAO = new DownloadDAO();
		$clipboardDAO = new ClipboardDAO();
		$fileDAO = new FileDAO();
		$forumThreadDAO = new ForumThreadDAO();
		$forumPostDAO = new ForumPostDAO();
		$authorDAO = new AuthorDAO();
		return
			$creditsPackageDAO->getCountByForeignKey('userId', $record['id']) > 0
			|| $downloadDAO->getCountByForeignKey('userId', $record['id']) > 0
			|| $clipboardDAO->getCountByForeignKey('userId', $record['id']) > 0
			|| $fileDAO->getCountByRecord('user', $record['id']) > 0
			|| $forumThreadDAO->getCountByForeignKey('userId', $record['id']) > 0
			|| $forumPostDAO->getCountByForeignKey('userId', $record['id']) > 0
			|| $authorDAO->getCountByForeignKey('userId', $record['id']) > 0;
	}

	protected function getDirectlyRelatedRecords(&$record) {
		$creditsPackageDAO = new CreditsPackageDAO();
		$downloadDAO = new DownloadDAO();
		$clipboardDAO = new ClipboardDAO();
		$fileDAO = new FileDAO();
		$forumThreadDAO = new ForumThreadDAO();
		$forumPostDAO = new ForumPostDAO();
		$authorDAO = new AuthorDAO();
		return array(
			'CreditsPackageDAO' => $creditsPackageDAO->getListByForeignKey('userId', $record['id']),
			'DownloadDAO' => $downloadDAO->getListByForeignKey('userId', $record['id']),
			'ClipboardDAO' => $clipboardDAO->getListByForeignKey('userId', $record['id']),
			// avatar
			'FileDAO' => $fileDAO->getListByRecord('user', $record['id']),
			// forum
			'ForumThreadDAO' => $forumThreadDAO->getListByForeignKey('userId', $record['id']),
			'ForumPostDAO' => $forumPostDAO->getListByForeignKey('userId', $record['id']),
			'AuthorDAO' => $authorDAO->getListByForeignKey('userId', $record['id'])
		);
	}

	/**
	 *
	 * @param string $userEmail
	 * @param string $password
	 * @return array - rekord użytkownika lub pusty
	 *
	 * W zasadzie powinno byc "getActiveUserByEmailAndPassword" ale
	 * musi byc kompatybilne z iCoreAccess
	 */
	public function getByNameAndPassword($userEmail, $password) {
		$db = CoreServices::get('db');
		$sql = '
			SELECT *
			FROM user
			WHERE
				userEmail = ' . $db->prepareInputValue($userEmail) . '
				AND userPassword = ' . $db->prepareInputValue($password) . '
				AND userState = \'active\'';
		$rows = $db->getRows($sql);
		if (!empty($rows) && sizeof($rows) == 1) {
			return $rows[0];
		}
		return $this->getRecordTemplate();
	}

	/**
	 * Powinno być raczej getActiveUserByEmail ale musi być zgodne z
	 * iCoreAccess
	 *
	 * @param string $userEmail
	 * @return array - rekord użytkownika (lub szablon rekordu)
	 */
	public function getByName($userEmail) {
		return $this->getActiveUserByEmail($userEmail);
	}

	protected function getDefaultOrderBySQL() {
		return 'userRegisterTime DESC';
	}

	public function delete(&$record) {
		// Rekord przekazany w parametrze może nie zawierać wszystkich potrzebnych informacji!
		$recordFull = $this->getRecordById($record['id']);
		if (empty($recordFull['id'])) {
			return;
		}
		// Wszystkie pola poza wymienionymi mają byc wyczyszczone
		$recordCopy = $this->getRecordTemplate();
		$recordCopy['id'] = $recordFull['id'];
		$recordCopy['userEmail'] = $recordFull['userEmail'];
		$recordCopy['userPassword'] = 'n/a';
		$recordCopy['userRegisterTime'] = $recordFull['userRegisterTime'];
		$recordCopy['userEraseRequestTime'] = $recordFull['userEraseRequestTime'];
		$recordCopy['userEraseTime'] = CoreUtils::getDateTime();
		$recordCopy['userState'] = 'deleted';
		parent::save($recordCopy);
	}

	public function getListForAutoSuggest($query, $limit) {
		$db = CoreServices::get('db');
		$queryConditionSQL = '0';
		$parts = explode(' ', $query);
		$names = array();
		for ($i = 0; $i < min(3, sizeof($parts)); $i++) {
			$names[] = $db->prepareInputValue($parts[$i] . '%');
		}
		foreach (array('userEmail', 'userCompanyName', 'userFirstName', 'userSurname', 'userNick') as $colName) {
			foreach ($names as $nameSQL) {
				$queryConditionSQL .= '
					OR ' . $colName . ' LIKE ' . $nameSQL;
			}
		}
		$sql = '
			SELECT *
			FROM user
			WHERE
				(' . $queryConditionSQL . ')
				AND userState = \'active\'
			ORDER BY userEmail';
		if ($limit) {
			$sql .= '
			LIMIT 0, ' . $db->prepareInputValue($limit);
		}
		return $db->getRows($sql);
	}

	public function getFilteredCount(&$filter) {
		$whereSQL = $this->whereSQLForFilter($filter) . ' AND userState != \'deleted\'';;
		return $this->getCountBySQLParams($whereSQL);
	}

	public function getFilteredPaginatedList(&$columns, &$filter, $pagination, $order = null) {
		parent::getFilteredPaginatedList();
		$columnsSQL = $columns ? implode(', ', array_keys($columns)) : null;
		$whereSQL = $this->whereSQLForFilter($filter) . ' AND userState != \'deleted\'';
		$orderBySQL = $this->orderSQL($columns, $order);
		$offset = null;
		$limit = null;
		if ($pagination->getType() == 'Standard') {
			$offset = $pagination->getCurrentOffset();
			$limit = $pagination->getMaxRecordsOnPage();
		}
		return $this->getListBySQLParams(
			$columnsSQL,
			$whereSQL,
			$orderBySQL,
			$offset,
			$limit
		);
	}

	public function getNewPasswordChangeCode(&$record) {
		return md5(CoreUtils::getTimeMicroseconds() . 'ly2bt94u6w') . 'G' . $record['id'];
	}

	public function getListForDeletion($dateFrom, $dateTo) {
		$db = CoreServices2::getDB();
		$sql = '
			SELECT
				id,
				userEmail,
				userCompanyName,
				userFirstName,
				userSurname,
				userNick,
				userEraseRequestTime
			FROM
				user
			WHERE
				userState = \'forDeletion\'';
		if (!empty($dateFrom)) {
			$sql .= '
				AND userEraseRequestTime >= ' . $db->prepareInputValue($dateFrom . ' 00:00:00');
		}
		if (!empty($dateTo)) {
			$sql .= '
				AND userEraseRequestTime <= ' . $db->prepareInputValue($dateTo . ' 23:59:59');
		}
		$sql .= '
			ORDER BY
				userEraseRequestTime DESC';
		return $db->getRows($sql);
	}

	public function getActivatedAccountsCount($dateFrom = null, $dateTo = null) {
		$db = CoreServices2::getDB();
		$sql = '
			SELECT
				count(id) as num
			FROM
				user
			WHERE
				1'; // '(userState = \'active\' OR userState = \'blocked\')'
		if (!empty($dateFrom)) {
			$sql .= '
				AND userRegisterTime >= ' . $db->prepareInputValue($dateFrom . ' 00:00:00');
		}
		if (!empty($dateTo)) {
			$sql .= '
				AND userRegisterTime <= ' . $db->prepareInputValue($dateTo . ' 23:59:59');
		}
		$row = $db->getRow($sql);
		return $row['num'];
	}

	public function getDeletedAccountsCount($dateFrom = null, $dateTo = null) {
		$db = CoreServices2::getDB();
		$sql = '
			SELECT
				count(id) as num
			FROM
				user
			WHERE
				userState = \'deleted\''; // 'OR userState = \'forDeletion\''
		if (!empty($dateFrom)) {
			$sql .= '
				AND userEraseTime >= ' . $db->prepareInputValue($dateFrom . ' 00:00:00');
		}
		if (!empty($dateTo)) {
			$sql .= '
				AND userEraseTime <= ' . $db->prepareInputValue($dateTo . ' 23:59:59');
		}
		$row = $db->getRow($sql);
		return $row['num'];
	}

	public function getFilteredPaginatedListForStats(&$columns, &$filter, $pagination, $order = null) {
		$db = CoreServices::get('db');
		$columnsSQL = $columns ? implode(', ', array_keys($columns)) : null;
		$columnsSQL = $this->adaptColumnsSQL($columnsSQL);
		$whereSQL = $this->whereSQLForFilter($filter);
		$columnsExtended = array_merge($columns, array('downloadCount'=>'downloadCount'));
		$orderBySQL = $this->orderSQL($columnsExtended, $order);
		$offset = null;
		$limit = null;
		if ($pagination->getType() == 'Standard') {
			$offset = $pagination->getCurrentOffset();
			$limit = $pagination->getMaxRecordsOnPage();
		}

		$sql = '
			SELECT ' . $columnsSQL . ', COUNT(download.id) AS downloadCount
			FROM
				user
				LEFT JOIN download ON (userId = user.id)
			WHERE ' . $whereSQL . '
			GROUP BY user.id
			ORDER BY downloadCount DESC, userCredits DESC';
		if ($limit) {
			if (!$offset) {
				$offset = '0';
			}
			$sql .= ' LIMIT ' . $offset . ', ' . $limit;
		}
		$rows = $db->getRows($sql);
		return $rows;
	}

	public function getFilteredCountForStats(&$filter) {
		$db = CoreServices::get('db');
		$whereSQL = $this->whereSQLForFilter($filter);
		$sql = '
			SELECT COUNT(userId) AS num
			FROM (
				SELECT userId
				FROM
					user
					LEFT JOIN download ON (userId = user.id)
				WHERE ' . $whereSQL . '
				GROUP BY user.id
			) as userTable';
		$row = $db->getRow($sql);
		return $row['num'];
	}

}
?>
