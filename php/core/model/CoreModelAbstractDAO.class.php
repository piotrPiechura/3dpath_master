<?php
/**
 * Abstract DAO for simple record with no language versions.
 */
abstract class CoreModelAbstractDAO {
	public function __construct() {}

	abstract protected function getTableName();
	
	abstract protected function getFields();

	/**
	 * Returns an array like:
	 * array(
	 *     'foreignTable1' => null,
	 *     'foreignTable2_rel1' => null,
	 *     'foreignTable2_rel2' => null
	 * )
	 */
	protected function getManyToManyRelations()	{
		return array();
	}
	
	abstract protected function getDefaultOrderBySQL();

	/**
	 * Returns an array with keys corresponding to the database columns.
	 * If one logical record is stored in more than one database table this
	 * function should return columns from all the tables involved.
	 * This function does not initialize '_manyToMany' field.
	 */
	
	public function getRecordTemplate() {
		return $this->getFields();
	}

	/**
	 * This function initializes '_manyToMany' field of a record.
	 * If this is a new record (id is empty) the field will contain
	 * return value of getManyToManyRelations() function.
	 * Otherwise it will look like this:
	 * array(
	 *     'foreignTable1' => array(2, 5, 11),
	 *     'foreignTable2_rel1' => array(3, 7, 13),
	 *     'foreignTable2_rel2' => null
	 * ) 
	 */
	public function initAllManyToManyRelations(&$record) {
		$record['_manyToMany'] = $this->getManyToManyRelations();
		if ($record['id']) {
			$relationNames = array_keys($record['_manyToMany']);
			foreach ($relationNames as $relationName) {
				$this->initManyToManyRelation($record, $relationName);
			}
		}
	}
	
	public function initManyToManyRelation(&$record, $relationName) {
		CoreUtils::checkConstraint(array_key_exists($relationName, $this->getManyToManyRelations()));
		$record['_manyToMany'][$relationName] = $this->getManyToManyForeignIds($relationName, $record['id']);
	}

	public function isRelated(&$record, $manyToManyRelationName, $foreignId) {
		if (!isset($record['_manyToMany'][$manyToManyRelationName])) {
			$this->initManyToManyRelation($record, $manyToManyRelationName);
		}
		return (
			!empty($record['_manyToMany'][$manyToManyRelationName])
			&& in_array($foreignId, $record['_manyToMany'][$manyToManyRelationName])
		);
	}

	/**
	 * Returns empty record if a record with given id does not exist.
	 * This function does not initialize '_manyToMany' field.
	 */
	
	public function getRecordById($id) {
		if ($id) {
			$rows = CoreServices2::getDB()->getRows($this->getRecordByIdSQL($id));
			if (sizeof($rows) == 1) {
				return $rows[0];
			}
		}
		return $this->getRecordTemplate();
	}

	
	protected function getRecordByIdSQL($id) {
		return 'SELECT * FROM ' . $this->getTableName() . ' WHERE id = ' . CoreServices2::getDB()->prepareInputValue($id);
	}
	
	
	public function getFilteredCount(&$filter) {
		$whereSQL = $this->whereSQLForFilter($filter);
		return $this->getCountBySQLParams($whereSQL);
	}

	public function getFilteredCountByForeignKey(&$filter, $foreignKeyName, $foreignKeyValue) {
		$whereSQL = $this->whereSQLForFilter($filter);
		$whereSQL .= ' AND ' . $foreignKeyName . ' = ' . CoreServices2::getDB()->prepareInputValue($foreignKeyValue);
		return $this->getCountBySQLParams($whereSQL);
	}
	
	protected function getCountBySQLParams($whereSQL) {
		$row = CoreServices2::getDB()->getRow($this->getCountSQL($whereSQL));
		return $row['num'];
	}

	/**
	 * Generic method taking SQL parameter.
	 */
	protected function getCountSQL($whereSQL = null) {
		$tableName = $this->getTableName();
		$sql = 'SELECT COUNT(*) AS num FROM ' . $this->getTableName();
		if ($whereSQL) {
			$sql .= ' WHERE ' . $whereSQL;
		}
		return $sql;
	}

	/**
	 * $order is a string like: 'colName &lt;' OR 'colName &gt;'; this parameter is checked
	 * against column list, to make sure that the resulting SQL is ok, so it can be passed
	 * directly from a form field.
	 */
	public function getFilteredPaginatedList(&$columns, &$filter, $pagination, $order = null) {
		$columnsSQL = $columns ? implode(', ', array_keys($columns)) : null;
		$whereSQL = $this->whereSQLForFilter($filter);
		
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

	public function getFilteredPaginatedListByForeignKey($foreignKeyName, $foreignKeyValue, &$columns, &$filter, $pagination, $order = null) {
		$columnsSQL = $columns ? implode(', ', array_keys($columns)) : null;
		$whereSQL = $this->whereSQLForFilter($filter);
		$whereSQL .= ' AND ' . $foreignKeyName . ' = ' . CoreServices2::getDB()->prepareInputValue($foreignKeyValue);
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

	/**
	 * $order is a string like: '<colName>_asc' OR '<colName>_desc;'
	 */
	protected function orderSQL(&$columns, $order) {
		$colNameLength = strrpos($order, '_');
		$colName = substr($order, 0, $colNameLength);
		$direction = strtoupper(substr($order, $colNameLength + 1));
		$defaultOrder = $this->getDefaultOrderBySQL();
		if (
			!empty($colName)	
			&& array_key_exists($colName, $columns)
			&& ($direction == 'ASC' || $direction == 'DESC')
		) {
			$orderBySQL = $colName . ' ' . $direction;
			$defaultOrderPrefix = substr($defaultOrder, 0, $colNameLength + 1); 
			if ($defaultOrderPrefix != $colName . ' ') {
				$orderBySQL .= ', ' . $defaultOrder;
			}
		}
		else {
			$orderBySQL = $defaultOrder;
		}
		return $orderBySQL;
	}

	public function getCountByForeignKey($foreignKeyName, $foreignKeyValue) {
		return $this->getCountBySQLParams(
			$foreignKeyName . ' = ' . CoreServices2::getDB()->prepareInputValue($foreignKeyValue)
		);
	}

	public function getListByForeignKey($foreignKeyName, $foreignKeyValue) {
		return $this->getListByForeignKeyAndSQLParams($foreignKeyName, $foreignKeyValue);
	}
	
	
	protected function getListByForeignKeyAndSQLParams(
		$foreignKeyName,
		$foreignKeyValue,
		$columnsSQL = null,
		$whereSQL = null,
		$orderBySQL = null,
		$offset = null,
		$limit = null
	) {
		if (empty($whereSQL)) {
			$whereSQL = '';
		}
		else {
			$whereSQL .= ' AND ';
		}
		$whereSQL .= $foreignKeyName . ' = ' . CoreServices2::getDB()->prepareInputValue($foreignKeyValue);
		return $this->getListBySQLParams(
			$columnsSQL,
			$whereSQL,
			$orderBySQL,
			$offset,
			$limit
		);
	}

	
	protected function getListBySQLParams(
		$columnsSQL = null,
		$whereSQL = null,
		$orderBy = null,
		$offset = null,
		$limit = null
	) {
		$sql = $this->getListSQL(
			$columnsSQL,
			$whereSQL,
			$orderBy,
			$offset,
			$limit
		);
		return CoreServices2::getDB()->getRows($sql);
	}

	
	protected function whereSQLForFilter(&$filterList) {
		$sql = '1';
		foreach ($filterList as $fieldName => $singleFilter) {
			$value = $singleFilter->getValue();
			if (!is_null($value)) {

				switch($singleFilter->getConditionType()) {
					case 'none':
						break;
					case 'exact':
					case 'select':
						$sql .= ' AND ' . $fieldName . ' = ' . CoreServices2::getDB()->prepareInputValue($value);
						break;
					case 'like':
						$sql .= ' AND ' . $fieldName . ' LIKE ' . CoreServices2::getDB()->prepareInputValue('%' . $value . '%');
						break;
					case 'moreThan':
						$sql .= ' AND ' . $fieldName . ' > ' . CoreServices2::getDB()->prepareInputValue($value);
						break;
					case 'lessThan':
						$sql .= ' AND ' . $fieldName . ' < ' . CoreServices2::getDB()->prepareInputValue($value);
						break;
					case 'moreThanOrEqual':
						$sql .= ' AND ' . $fieldName . ' >= ' . CoreServices2::getDB()->prepareInputValue($value);
						break;
					case 'lessThanOrEqual':
						$sql .= ' AND ' . $fieldName . ' <= ' . CoreServices2::getDB()->prepareInputValue($value);
						break;
					case 'multipleLikeWithOr':
						$fields = $singleFilter->getFields();
						$sql .= ' AND (';
						$tmpSql = '';
						foreach($fields as $singleField) {
							$tmpSql .= 'OR ' . $singleField . ' LIKE ' . CoreServices2::getDB()->prepareInputValue('%' . $value . '%') . ' ';
						}
						$tmpSql = trim($tmpSql);
						$tmpSql = ltrim($tmpSql, 'OR');
						$sql .= $tmpSql . ')';
						break;
					case 'between':
						if(!empty($value['min']) && empty($value['max'])) {
							$sql .= ' AND ' . $fieldName . ' >= ' . CoreServices2::getDB()->prepareInputValue($value['min']);
						} else if(empty($value['min']) && !empty($value['max'])) {
							$sql .= ' AND ' . $fieldName . ' <= ' . CoreServices2::getDB()->prepareInputValue($value['max']);
						} else if(!empty($value['min']) && !empty($value['max'])) {
							$sql .= ' AND ' . $fieldName . ' BETWEEN ' . CoreServices2::getDB()->prepareInputValue($value['min']) . ' AND ' . CoreServices2::getDB()->prepareInputValue($value['max']);
						} else {
							
						}
						break;
					default:
						break;
				}
			}
		}
		return $sql;
	}

	protected function getDefaultColumnList() {
		return $this->getTableName() . '.' . implode(
			', ' . $this->getTableName() . '.',
			array_keys($this->getRecordTemplate())
		);
	}

	/**
	 * Use it if there is some JOIN in a query and 'id' column name is ambiguous.
	 */
	protected function adaptColumnsSQL($columnsSQL) {
		$idFullName = $this->getTableName() . '.id';
		$modified = str_replace(' id,', ' ' . $idFullName . ',', ' ' . $columnsSQL);
		if (strpos($modified, $idFullName) === False) {
			$modified = $idFullName . ', ' . $modified;
		}
		$stateColumnName = $this->getTableName() . 'State';
		if (array_key_exists($stateColumnName, $this->getFields())) {
			if (
				strstr($modified, $stateColumnName . ',') === False
				&& substr(trim($modified), -strlen($stateColumnName)) != $stateColumnName
			) {
				$modified .= ', ' . $stateColumnName;
			}
		}
		return $modified;
	}

	protected function getLimitSQLClause ($offset, $limit) {
		$sql = '';
		if ($limit) {
			if (!$offset) {
				$offset = '0';
			}
			$sql .= ' LIMIT ' . $offset . ', ' . $limit;
		}
		return $sql;
	}
	
	/**
	 * Generic method taking SQL parameters.
	 */
	
	protected function getListSQL(
		$columnsSQL = null,
		$whereSQL = null,
		$orderBySQL = null,
		$offset = null,
		$limit = null
	) {
		$tableName = $this->getTableName();
		if (empty($columnsSQL)) {
			$columnsSQL = $this->getDefaultColumnList();
		}
		$columnsSQL = $this->adaptColumnsSQL($columnsSQL);
		$sql = '
			SELECT ' . $columnsSQL . '
			FROM ' . $tableName;
		if ($whereSQL) {
			$sql .= ' WHERE ' . $whereSQL;
		}
		if (!$orderBySQL) {
			$orderBySQL = $this->getDefaultOrderBySQL();
		}
		$sql .= ' ORDER BY ' . $orderBySQL ;
		$sql .= $this->getLimitSQLClause($offset, $limit);
		return $sql;
	}

/////////////////////////////////////////////////////////////////////////////////////////////
// vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv MANY TO MANY vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
	/**
	 * Returns array like:
	 * array(
	 *     'foreignTableName' => 'someTableName',
	 *     'joinTableName' => 'myTableName_someTableName_relationName'
	 * )
	 */
    protected function decodeRelationName($relationName) {
		$parts = explode('_', $relationName);
		$foreignTableName = $parts[0];
		$tableName = $this->getTableName();
        $joinTableName = (
			$tableName < $foreignTableName
			? $tableName . '_' . $foreignTableName
			: $foreignTableName . '_' . $tableName
		);
		if (sizeof($parts) > 1) {
			$joinTableName .= '_' . $parts[1];
		}
		return array('foreignTableName' => $foreignTableName, 'joinTableName' => $joinTableName);
    }

    protected function getCountByManyToManySQL(
        $relationName,
        $foreignId,
        $whereSql = null
    ) {
		$tableName = $this->getTableName();
		$relTableNames = $this->decodeRelationName($relationName);
		if (empty($columnsSQL)) {
			$columnsSQL = implode(', ', array_keys($this->getRecordTemplate()));
		}
		$sql = '
			SELECT COUNT(*) AS num
			FROM ' . $tableName . ', ' . $relTableNames['joinTableName'] . '
			WHERE
				' . $relTableNames['joinTableName'] . '.' . $relTableNames['foreignTableName'] . 'Id = ' . CoreServices2::getDB()->prepareInputValue($foreignId) . '
				AND ' . $relTableNames['joinTableName'] . '.' . $tableName . 'Id = ' . $tableName . '.id ';
		if ($whereSQL) {
			$sql .= ' AND ' . $whereSQL;
		}
		return $sql;
    }

	public function getListByManyToMany (
		$relationName,
        $foreignId,
        $columns = null,
        $order = null,
        $offset = null,
        $limit = null
	) {
		$columnsSQL = $columns ? implode(', ', array_keys($columns)) : null;
		$orderBySQL = $this->orderSQL($columns, $order);
		$sql = $this->getListByManyToManySQL (
			$relationName,
			$foreignId,
			$columnsSQL,
			null,
			$orderBySQL,
			$offset,
			$limit
		);
		return CoreServices2::getDB()->getRows($sql);
	}

    protected function getListByManyToManySQL (
		$relationName,
        $foreignId,
        $columnsSQL = null,
        $whereSQL = null,
        $orderBySQL = null,
        $offset = null,
        $limit = null
    ) {
		$tableName = $this->getTableName();
		$relTableNames = $this->decodeRelationName($relationName);
		if (empty($columnsSQL)) {
			$columnsSQL = implode(', ', array_keys($this->getRecordTemplate()));
		}
		$sql = '
			SELECT ' . $columnsSQL . '
			FROM ' . $tableName . ', ' . $relTableNames['joinTableName'] . '
			WHERE
				' . $relTableNames['joinTableName'] . '.' . $relTableNames['foreignTableName'] . 'Id = ' . CoreServices2::getDB()->prepareInputValue($foreignId) . '
				AND ' . $relTableNames['joinTableName'] . '.' . $tableName . 'Id = ' . $tableName . '.id ';
		if ($whereSQL) {
			$sql .= ' AND ' . $whereSQL;
		}
		if ($orderBySQL) {
			$sql .= ' ORDER BY ' . $orderBySQL ;
		}
		if ($limit) {
			if (!$offset) {
				$offset = '0';
			}
			$sql .= ' LIMIT ' . $offset . ', ' . $limit;
		}
		return $sql;
    }

	protected function deleteAllManyToManyRelations($id) {
		foreach (array_keys($this->getManyToManyRelations()) as $relationName) {
			$this->deleteManyToManyRelation($relationName, $id);
		}
	}

	protected function getManyToManyForeignIdsSQL($relTableNames, $id) {
		return '
			SELECT ' . $relTableNames['foreignTableName'] . 'Id
			FROM ' . $relTableNames['joinTableName'] . '
			WHERE ' . $this->getTableName() . 'Id = ' . CoreServices2::getDB()->prepareInputValue($id);
	}

	public function getManyToManyForeignIds($relationName, $id) {
		if (!$id) {
			return null;
		}
		$relTableNames = $this->decodeRelationName($relationName);
		$rows = CoreServices2::getDB()->getRows(
			$this->getManyToManyForeignIdsSQL($relTableNames, $id)
		);
		$result = array();
		foreach ($rows as $row) {
			$result[] = $row[$relTableNames['foreignTableName'] . 'Id'];
		}
		return $result;
	}

	protected function deleteManyToManyRelationSQL($relationName, $id) {
		$relTableNames = $this->decodeRelationName($relationName);
		return '
			DELETE FROM ' . $relTableNames['joinTableName'] . '
			WHERE ' . $this->getTableName() . 'Id = ' . CoreServices2::getDB()->prepareInputValue($id);
	}

	protected function deleteManyToManyRelation($relationName, $id) {
		CoreServices2::getDB()->change(
			$this->deleteManyToManyRelationSQL($relationName, $id)
		);
	}

	protected function saveManyToManyForeignIdSQL($relationName, $id, $foreignId) {
		$relTableNames = $this->decodeRelationName($relationName);
		return '
			INSERT INTO ' . $relTableNames['joinTableName'] . '
				(' .$this->getTableName()  . 'Id, ' . $relTableNames['foreignTableName'] . 'Id)
			VALUES
				(
					' . CoreServices2::getDB()->prepareInputValue($id) . ',
					' . CoreServices2::getDB()->prepareInputValue($foreignId) . '
				)';
	}

	protected function saveManyToManyForeignIds($relationName, $id, $newForeignIds = null) {
		$this->deleteManyToManyRelation($relationName, $id);
		if (!empty($newForeignIds)) {
			foreach ($newForeignIds as $foreignId) {
				CoreServices2::getDB()->change(
					$this->saveManyToManyForeignIdSQL($relationName, $id, $foreignId)
				);
			}
		}
	}
// ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^ MANY TO MANY ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
/////////////////////////////////////////////////////////////////////////////////////////////

	/**
	 * Nothing is checked at this stage - it is assumed that the record
	 * can be saved without loss of data consistency or any other problems.
	 * Many to many relations stored in the record are saved in database.
	 */
	public function save(&$record) {
		if ($record['id']) {
			$this->update($record);
		}
		else {
			$this->insert($record);
		}
		if (!empty($record['_manyToMany'])) {
			foreach ($record['_manyToMany'] as $relationName => $newForeignIds) {
				$this->saveManyToManyForeignIds($relationName, $record['id'], $newForeignIds);
			}
		}
	}

	protected function update(&$record) {
		CoreServices2::getDB()->change($this->updateSQL($record));
	}

	protected function insert(&$record) {
		$id = CoreServices2::getDB()->insertRow($this->insertSQL($record));
		$record['id'] = $id;
	}

	protected function updateSQL(&$record) {
		$sql = 'UPDATE ' . $this->getTableName() . ' SET ';
		$i = 0;
		foreach (array_keys($this->getFields()) as $fieldName) {
			if ($i > 0) {
				$sql .= ', ';
			}
			$sql .= ' ' . $fieldName . ' = ' . CoreServices2::getDB()->prepareInputValue($record[$fieldName]);
			$i ++;
		}
		$sql .= ' WHERE id = ' . CoreServices2::getDB()->prepareInputValue($record['id']);
		return $sql;
	}

	protected function insertSQL(&$record) {
		$sql = 'INSERT INTO ' . $this->getTableName() . ' (';
		$i = 0;
		foreach (array_keys($this->getFields()) as $fieldName) {
			if ($i > 0) {
				$sql .= ', ';
			}
			$sql .= ' ' . $fieldName;
			$i ++;
		}
		$sql .= ') VALUES (';
		$i = 0;
		foreach (array_keys($this->getFields()) as $fieldName) {
			if ($i > 0) {
				$sql .= ', ';
			}
			$sql .= ' ' . CoreServices2::getDB()->prepareInputValue($record[$fieldName]);
			$i ++;
		}
		$sql .= ')';
		return $sql;
	}

	/**
	 * This function doesn't delete the dependent records - controller
	 * must do it explicitly.
	 * However, this function does delete many to many links.
	 * Nothing is checked at this stage - assume everything is ok.
	 * It's not enough to pass id to this function, because in some
	 * subclasses it can also remove disk files described in the record.
	 */
	public function delete(&$record) {
		if ($record['id']) {
			$this->deleteAllManyToManyRelations($record['id']);
			CoreServices2::getDB()->change($this->deleteSQL($record['id']));
		}
	}

	/**
	 * Ta funkcja, jak sama nazwa wskazuje usuwa rekord wraz z wszystkimi
	 * rekordami zależnymi, kaskadowo.
	 */
	public function deleteCascade(&$record) {
		$related = $this->getRelatedRecords($record);
		foreach ($related as $daoClass => $recordList) {
			$dao = new $daoClass();
			foreach ($recordList as $relatedRecord) {
				$dao->delete($relatedRecord);
			}
		}
		$this->delete($record);
	}
	
	protected function deleteSQL($id) {
		return 'DELETE FROM ' . $this->getTableName() . ' WHERE id = ' . CoreServices2::getDB()->prepareInputValue($id);
	}

	public function getRelatedRecords(&$record) {
		$relatedRecords = array();
		$this->addRecursivelyRelatedRecords($record, $relatedRecords);
		return $relatedRecords;
	}

	public function hasRelatedRecords(&$record) {
		// Zakładamy że hasNumerousDirectlyRelatedRecords() informuje o WSZYSTKICH
		// rekordach zależnych
		return $this->hasNumerousDirectlyRelatedRecords($record);
		// Wersja która zakłada, że hasNumerousDirectlyRelatedRecords()
		// zwraca rzeczywiście tylko potencjalnie liczne relacje i w związku z tym
		// wymaga jeszcze dokładnego sprawdzenia tych "nielicznych".
		//if ($this->hasNumerousDirectlyRelatedRecords($record)) {
		//	return True;
		//}
		//foreach ($this->getDirectlyRelatedRecords($record) as $records) {
		//	if (!empty($records)) {
		//		return True;
		//	}
		//}
		//return False;

		// @TODO: właściwie to jest troszeczkę do kitu:
		// powinna być używana tylko ta metoda i powinna używać sprawdzeń typu 'getCount'.
		// metody hasNumerousDirectlyRelatedRecords() w ogóle nie powinno być.
	}

	/**
	 * Tu trzeba sprawdzić czy występują rekordy powiązane, o ile może ich być bardzo dużo
	 * i pobranie listy za pomocą getDirectlyRelatedRecords() byłoby nieefektywne
	 * @param array $record
	 * @return boolean
	 */
	abstract protected function hasNumerousDirectlyRelatedRecords(array &$record);

	abstract protected function getDirectlyRelatedRecords(&$record);

	protected function addRecursivelyRelatedRecords(&$record, &$result) {
		$directlyRelatedRecords = $this->getDirectlyRelatedRecords($record);
		foreach ($directlyRelatedRecords as $daoClassName => $recordList) {
			if (!empty($recordList)) {
				if (!array_key_exists($daoClassName, $result)) {
					$result[$daoClassName] = array();
				}
				$dao = new $daoClassName();
				foreach ($recordList as $relatedRecord) {
					$relatedRecordId = $relatedRecord['id'];
					if (!array_key_exists($relatedRecordId, $result[$daoClassName])) {
						$result[$daoClassName][$relatedRecordId] = $relatedRecord;
						$dao->addRecursivelyRelatedRecords($relatedRecord, $result);
					}
				}
			}
		}
	}

	protected function getPatternArray($pattern) {
		$patternLength = strlen($pattern);
		$patternArray = array();
		$patternArrayIndex = 0;
		for ($i = 0; $i < $patternLength; $i++) {
			if ($pattern[$i] == '<') {
				$patternArrayIndex++;
				$patternArray[$patternArrayIndex] = array('column', '');
			}
			elseif ($pattern[$i] == '>') {
				$patternArrayIndex++;
				$patternArray[$patternArrayIndex] = array('text', '');
			}
			else {
				CoreUtils::checkConstraint(isset($patternArray[$patternArrayIndex][1]));
				$patternArray[$patternArrayIndex][1] .= $pattern[$i];
			}
		}
		return $patternArray;
	}

	/**
	 * Takes record list, row description pattern like '<personFirstName> <personSurname>'
	 * and optional empty row description pattern - if null, empty option is not added.
	 */
	
	public function modifyListForSelect(&$recordList, $pattern, $emptyRowDescription = null) {
		return $this->modifyListForSelectBySpecificColumn(
			$recordList,
			'id',
			$pattern,
			$emptyRowDescription
		);
	}

	/**
	 * Takes record list, value column, row description pattern
	 * like '<personName> - <cityName>' and optional empty row description pattern
	 * - if null, empty option is not added.
	 */
	public function modifyListForSelectBySpecificColumn(&$recordList, $valueColumn, $pattern, $emptyRowDescription = null) {
		$patternArray = $this->getPatternArray($pattern);
		$result = array();
		if (!is_null($emptyRowDescription)) {
			$result[0] = $emptyRowDescription;
		}
		
		foreach ($recordList as $record) {
			$description = '';
			foreach ($patternArray as $patternPart) {
				if ($patternPart[0] == 'column') {
					$description .= $record[$patternPart[1]];
				}
				else {
					$description .= $patternPart[1];
				}
			}
			$result[$record[$valueColumn]] = $description;
		}

		return $result;
	}

	/**
	 * Uwaga: zmieniony interfejs metody!
	 * Poprzednia wersja przyjmowała "kolumnę do sortowania" która mogła się składać
	 * z nazwy kolumny i "ASC" lub "DESC" a to wbrew ważnej konwencji że
	 * SQL JEST WYŁĄCZNIE W DAO!
	 */
	public function getDistinctList($columns, $order) {
		$cols = array();
		foreach ($columns as $col) {
			$cols[$col] = null;
		}
		$orderSQL = $this->orderSQL($cols, $order);
		if (empty($orderSQL)) {
			$orderSQL = $this->getDefaultOrderBySQL();
		}
		$sql = '
			SELECT DISTINCT ' . implode(', ', $columns) . '
			FROM ' . $this->getTableName() . '
			WHERE 1';
		foreach ($columns as $column) {
			$sql .= ' AND NOT ISNULL(' . $column . ') AND ' . $column . ' != \'\'';
		}
		$sql .= '
			ORDER BY ' . $orderSQL;
		return CoreServices2::getDB()->getRows($sql);
	}

	public function getAllIds() {
		$sql = '
			SELECT id
			FROM ' . $this->getTableName() . '
			WHERE 1
			ORDER BY id';
		$rows = CoreServices2::getDB()->getRows($sql);
		$result = array();
		foreach ($rows as $row) {
			$result[] = $row['id'];
		}
		return $result;
	}

	// @TODO:
	// to było przerobione przez Damiana, który używał tego do robienia pewnych brzydkich rzeczy.
	//	protected function getFieldsWithTablePrefix($alias = null) {
	//		$prefixedFields = array();
	//		if(!empty($alias)) {
	//			$tableName = $alias;
	//		} else {
	//			$tableName = $this->getTableName();
	//		}
	//		foreach(array_keys($this->getFields()) as $field) {
	//			$prefixedFields[$tableName . '.' . $field] = $tableName . '.' . $field;
	//		}
	//		return $prefixedFields;
	//	}
}
?>
