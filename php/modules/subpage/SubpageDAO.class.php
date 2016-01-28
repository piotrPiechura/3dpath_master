<?php
class SubpageDAO extends CoreModelAbstractDAO {
	protected function getTableName() {
		return 'subpage';
	}

	protected function getFields() {
		return array(
			'id' => null,
			'subpageState' => null,
			'subpageLeft' => null,
			'subpageRight' => null,
			'subpageLevel' => null,
			'subpageParentId' => null,
			// 'subpageIsEditable' => null,
			// 'subpageIsMovable' => null,
			// 'subpageIsRemovable' => null,
			'subpageMaxChildren' => null,
			// 'subpageMaxSubtreeHeight' => null,
			'subpageLoginRequired' => null,
			'subpageModule' => null,
			'subpageMode' => null,
			'subpageCaption' => null,
			'subpageLead' => null,
			'subpageTitle' => null,
			'subpageContent' => null
		);
	}

	public function getRootId() {
		$db = CoreServices2::getDB();
		$sql = '
			SELECT id
			FROM subpage
			WHERE
				subpageLeft = 1
				AND subpageLevel = -1
		';
		$rows = $db->getRows($sql);
		if (sizeof($rows) != 1) {
			return null;
		}
		return $rows[0]['id'];
	}

	public function getRecordByModuleAndMode($module, $mode) {
		$whereSQL = '
			subpageModule = ' . CoreServices::get('db')->prepareInputValue($module) . '
			AND subpageMode = ' . CoreServices::get('db')->prepareInputValue($mode);
		$list = $this->getListBySQLParams(
			null,
			$whereSQL,
			null,
			0,
			1
		);
		if (!empty($list[0])) {
			return $list[0];
		}
		throw new CoreException('There is no subpage record for module \'' . $module . '\', mode \'' . $mode . '\'');
	}

	public function getChildrenCount(&$record) {
		CoreUtils::checkConstraint(!empty($record['id']));
		$db = CoreServices2::getDB();
		$sql = '
			SELECT COUNT(*) AS num
			FROM subpage
			WHERE
				subpageParentId = ' . $db->prepareInputValue($record['id']);
		$row = $db->getRow($sql);
		return $row['num'];
	}

	public function getChildren(&$record, $guestView = false) {
		if (empty($record['id'])) {
			return null;
		}
		$db = CoreServices::get('db');
		$whereSQL = 'subpageParentId = ' . $db->prepareInputValue($record['id']);
		if ($guestView) {
			$whereSQL .= ' AND (ISNULL(subpageLoginRequired) OR subpageLoginRequired = 0)';
		}
		return $this->getListBySQLParams(
			'id, subpageLeft, subpageRight, subpageCaption, subpageLead, subpageLoginRequired, subpageModule, subpageMode',
			$whereSQL
		);
	}

	/**
	 * Navigation path that is visible from the given subpage.
	 */
	public function getAncestors(&$record, $guestView = false) {
		if (empty($record['id'])) {
			return null;
		}
		$db = CoreServices2::getDB();
		$whereSQL = '
			subpageLeft <= ' . $db->prepareInputValue($record['subpageLeft']) . '
			AND subpageRight >= ' . $db->prepareInputValue($record['subpageRight']);
		if ($guestView) {
			$whereSQL .= ' AND (ISNULL(subpageLoginRequired) OR subpageLoginRequired = 0)';
		}
		return $this->getListBySQLParams(
			$this->getListedFieldsSQL(),
			$whereSQL
		);
	}

	/**
	 * This must be triggered by a controller.
	 * Keeping consistency of the tree structure is controllers' responsibility.
	 */
	public function updateTreeOnInsert(&$record) {
		$db = CoreServices2::getDB();
		$difference = intval($record['subpageRight']) - intval($record['subpageLeft']) + 1;
		$sql = '
			UPDATE subpage
			SET subpageRight = subpageRight + ' . $difference . '
			WHERE
				id != ' . $db->prepareInputValue($record['id']) . '
				AND subpageRight >= ' . $db->prepareInputValue($record['subpageLeft']);
		$db->change($sql);
		$sql = '
			UPDATE subpage
			SET subpageLeft = subpageLeft + ' . $difference . '
			WHERE
				id != ' . $db->prepareInputValue($record['id']) . '
				AND subpageLeft >= ' . $db->prepareInputValue($record['subpageLeft']);
		$db->change($sql);
	}

	/**
	 * It is enough to call this function only once after the whole subtree was removed;
	 * of course in such case the parameter is the subtree root.
	 * This must be triggered by a controller.
	 * Keeping consistency of the tree structure is controllers' responsibility.
	 */
	public function updateTreeOnDelete(&$record) {
		$db = CoreServices2::getDB();
		$difference = intval($record['subpageRight']) - intval($record['subpageLeft']) + 1;
		$sql = '
			UPDATE subpage
			SET subpageLeft = subpageLeft - ' . $difference . '
			WHERE
				subpageLeft > ' . $db->prepareInputValue($record['subpageRight']);
		$db->change($sql);
		$sql = '
			UPDATE subpage
			SET subpageRight = subpageRight - ' . $difference . '
			WHERE
				subpageRight > ' . $db->prepareInputValue($record['subpageRight']);
		$db->change($sql);
	}

	/**
	 * Funkcja używana tylko wtedy, gdy w jednej transakcji b.d. poprawiamy
	 * pozycję WSZYSTKICH węzłów w strukturze menu. Ona zmienia parametry tylko jednego
	 * rekordu, nie zmieniając parametrów jego sąsiadów, a więc może chwilowo psuć
	 * strukturę drzewa lewo-prawo!
	 *
	 * @param <type> $record
	 */
	public function updateNodePosition(&$record) {
		$db = CoreServices::get('db');
		$sql = '
			UPDATE subpage
			SET
				subpageLeft     = ' . $db->prepareInputValue($record['subpageLeft']) . ',
				subpageRight    = ' . $db->prepareInputValue($record['subpageRight']) . ',
				subpageLevel    = ' . $db->prepareInputValue($record['subpageLevel']) . ',
				subpageParentId = ' . $db->prepareInputValue($record['subpageParentId']) . '
			WHERE id = ' . $db->prepareInputValue($record['id']);
		$db->change($sql);
	}

	protected function hasNumerousDirectlyRelatedRecords(array &$record) {
		$fileDAO = new FileDAO();
		return
			$fileDAO->getCountByRecord('subpage', $record['id']) > 0
			|| $this->getChildrenCount($record) > 0;
	}

	protected function getDirectlyRelatedRecords(&$record) {
		$fileDAO = new FileDAO();
		return array(
			'SubpageDAO' => $this->getChildren($record),
			'FileDAO' => $fileDAO->getListByRecord('subpage', $record['id'])
		);
	}

	protected function getDefaultOrderBySQL() {
		return 'subpageLeft';
	}

	public function getListSimple() {
		return $this->getListBySQLParams('
			id,
			subpageState,
			subpageLeft,
			subpageRight,
			subpageLevel,
			subpageParentId,
			subpageIsEditable,
			subpageIsMovable,
			subpageIsRemovable,
			subpageMaxChildren,
			subpageMaxSubtreeHeight,
			subpageLoginRequired,
			subpageModule,
			subpageMode,
			subpageCaption,
			subpageLead
		');
	}

	
	public function getFullIndexedList() {
		$simpleList = $this->getListSimple();
		$result = array();
		foreach ($simpleList as $record) {
			$result[$record['id']] = $record;
		}
		return $result;
	}
}
?>