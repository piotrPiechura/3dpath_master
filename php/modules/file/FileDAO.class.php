<?php
class FileDAO extends CoreModelAbstractDAO {
	protected function getTableName() {
		return 'file';
	}

	protected function getFields() {
		return array(
			'id' => null,
			'modelFileTypeId' => null,
			'fileUpdateTime' => null,
			'fileBaseName' => null,
			'fileExtension' => null,
			'fileCategory' => null,
			'fileMimeType' => null,
			'recordType' => null,
			'recordId' => null,
			'filePosition' => null,
			'fileOrder' => null,
			'fileTitle' => null
		);
	}

	protected function hasNumerousDirectlyRelatedRecords(array &$record) {
		$downloadDAO = new DownloadDAO();
		$downloadCount = $downloadDAO->getCountByForeignKey('fileId', $record['id']);
		return $downloadCount > 0;
	}

	protected function getDirectlyRelatedRecords(&$record) {
		$downloadDAO = new DownloadDAO();
		return array(
			'DownloadDAO' => $downloadDAO->getListByForeignKey('fileId', $record['id'])
		);
	}

	protected function getDefaultOrderBySQL() {
		return 'fileOrder, fileTitle';
	}

	public function getRecordById($id) {
		if ($id) {
			$db = CoreServices2::getDB();
			$sql = '
				SELECT
					`file`.*, modelFileTypeName
				FROM
					`file`
					LEFT JOIN modelFileType ON (filePosition = \'main\' AND modelFileTypeId = modelFileType.id)
				WHERE
					`file`.id = ' . $db->prepareInputValue($id);
			$rows = $db->getRows($sql);
			if (sizeof($rows) == 1) {
				return $rows[0];
			}
		}
		return $this->getRecordTemplate();
	}

	protected function getRecordByIdSQL($id) {
		CoreUtils::checkConstraint(false);
	}

	public function getListByRecord($recordType, $recordId, $fileCategory = null, $filePosition = null) {
		$db = CoreServices2::getDB();
		$sql = '
			SELECT
				`file`.*, modelFileTypeName
			FROM
				`file`
				LEFT JOIN modelFileType ON (filePosition = \'main\' AND modelFileTypeId = modelFileType.id)
			WHERE
				recordType = ' . $db->prepareInputValue($recordType) . '
				AND recordId = ' . $db->prepareInputValue($recordId);
		if ($fileCategory) {
			$sql .=
				' AND fileCategory = ' . $db->prepareInputValue($fileCategory);
		}
		if ($filePosition) {
			$sql .=
				' AND filePosition = ' . $db->prepareInputValue($filePosition);
		}
		$sql .= '
			ORDER BY
				' . $this->getDefaultOrderBySQL();
		return $db->getRows($sql);
	}

	public function getMaxOrderByRecord($recordType, $recordId, $fileCategory, $filePosition) {
		$db = CoreServices2::getDB();
		$sql = '
			SELECT
				COUNT(*) AS num, MAX(fileOrder) AS maxOrder
			FROM
				`file`
			WHERE
				recordType = ' . $db->prepareInputValue($recordType) . '
				AND recordId = ' . $db->prepareInputValue($recordId) . '
				AND fileCategory = ' . $db->prepareInputValue($fileCategory) . '
				AND filePosition = ' . $db->prepareInputValue($filePosition);
		$row = $db->getRow($sql);
		if ($row['num'] == 0) {
			return -1;
		}
		return $row['maxOrder'];
	}

	public function getCountByRecord($recordType, $recordId, $fileCategory = null, $filePosition = null) {
		$db = CoreServices2::getDB();
		$sql = '
			SELECT
				COUNT(*) AS num
			FROM
				`file`
			WHERE
				recordType = ' . $db->prepareInputValue($recordType) . '
				AND recordId = ' . $db->prepareInputValue($recordId);
		if ($fileCategory) {
			$sql .=
				' AND fileCategory = ' . $db->prepareInputValue($fileCategory);
		}
		if ($filePosition) {
			$sql .=
				' AND filePosition = ' . $db->prepareInputValue($filePosition);
		}
		$row = $db->getRow($sql);
		return $row['num'];
	}

	/**
	 * Useful for banner management
	 */
	public function getListByRecordList($recordType, &$recordIds, $fileCategory = null, $filePosition = null) {
		if (empty($recordIds)) {
			return array();
		}
		$db = CoreServices2::getDB();
		$preparedIds = array();
		foreach ($recordIds as $id) {
			$preparedIds[] = $db->prepareInputValue($id);
		}
		$sql = '
			SELECT
				`file`.*, modelFileTypeName
			FROM
				`file`
				LEFT JOIN modelFileType ON (filePosition = \'main\' AND modelFileTypeId = modelFileType.id)
			WHERE
				recordType = ' . $db->prepareInputValue($recordType) . '
				AND recordId IN (' . implode(', ', $preparedIds) . ')';
		if ($fileCategory) {
			$sql .=
				' AND fileCategory = ' . $db->prepareInputValue($fileCategory);
		}
		if ($filePosition) {
			$sql .=
				' AND filePosition = ' . $db->prepareInputValue($filePosition);
		}
		$sql .= '
			ORDER BY
				recordType, recordId, fileOrder, fileTitle';
		$rows = $db->getRows($sql);
		$result = array();
		foreach ($rows as $row) {
			if (empty($result[$row['recordId']])) {
				$result[$row['recordId']] = array();
			}
			$result[$row['recordId']][$row['id']] = $row;
		}
		return $result;
	}

	/**
	 * Powinno byc raczej getFirstImageListByRecordIds
	 * 
	 * @param string $recordType
	 * @param array $recordIds
	 * @param string $filePosition
	 * @return array
	 */
	public function getFirstImageListByRecordList($recordType, &$recordIds, $filePosition) {
		if (empty($recordIds)) {
			return array();
		}
		$db = CoreServices2::getDB();
		$preparedIds = array();
		foreach ($recordIds as $id) {
			$preparedIds[] = $db->prepareInputValue($id);
		}
		$idsSQL = implode(', ', $preparedIds);
		$recordTypeSQL = $db->prepareInputValue($recordType);
		$fileCategorySQL = $db->prepareInputValue('image');
		$filePositionSQL = $db->prepareInputValue($filePosition);
		$sql = '
			SELECT
				`file`.*
			FROM
				`file`, (
					SELECT recordId, MIN(fileOrder) AS _firstFileOrder
					FROM `file` AS _file
					WHERE
						recordType = ' . $recordTypeSQL . '
						AND recordId IN (' . $idsSQL . ')
						AND fileCategory = ' . $fileCategorySQL . '
						AND filePosition = ' . $filePositionSQL . '
					GROUP BY
						recordId
				) AS _helper
			WHERE
				recordType = ' . $recordTypeSQL . '
				AND _helper.recordId = `file`.recordId
				AND `file`.fileOrder = _firstFileOrder
				AND `file`.filePosition = ' . $filePositionSQL;
		$rows = $db->getRows($sql);
 		$res = array();
 		foreach ($rows as $row) {
			$res[$row['recordId']] = $row;
 		}
		return $res;
	}

	public function save(&$record, &$uploadStruct = null) {
		CoreUtils::checkConstraint(!empty($uploadStruct) || !empty($record['id']));
		if (!empty($uploadStruct)) {
			$this->saveDiskFile($record, $uploadStruct, $record['fileCategory']);
		}
		if (empty($record['fileOrder'])) {
			$record['fileOrder'] = 0;
		}
		parent::save($record);
	}

	public function delete(&$record) {
		$this->deleteDiskFile($record);
		parent::delete($record);
	}

	protected function saveDiskFile(&$record, &$uploadStruct) {
		$uploadManager = CoreServices2::getFiles();
		if ($record['fileBaseName']) {
			$uploadManager->removeFile(
				$record['fileBaseName'],
				$record['fileExtension'],
				($record['fileCategory'] == 'image')
			);
		}
		$fileNameStruct = $uploadManager->saveUploadedFile($uploadStruct, $record['fileCategory']);
		$record['fileBaseName'] = $fileNameStruct['baseName'];
		$record['fileExtension'] = $fileNameStruct['extension'];
		$record['fileMimeType'] = $uploadStruct['type'];
		$record['fileUpdateTime'] = CoreUtils::getDateTime();
	}

	protected function deleteDiskFile(&$record) {
		if ($record['fileBaseName']) {
			CoreServices2::getFiles()->removeFile(
				$record['fileBaseName'],
				$record['fileExtension'],
				($record['fileCategory'] == 'image')
			);
			$record['fileBaseName'] = null;
			$record['fileExtension'] = null;
			$record['fileMimeType'] = null;
		}
	}
}
?>