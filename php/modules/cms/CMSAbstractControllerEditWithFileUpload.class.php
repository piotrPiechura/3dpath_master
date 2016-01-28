<?php
abstract class CMSAbstractControllerEditWithFileUpload extends CMSAbstractControllerEdit {
	/**
	 * @var FileDAO
	 */
	protected $fileDAO = null;
	/**
	 * @var TmpRecordDAO
	 */
	protected $tmpRecordDAO = null;
	/**
	 *
	 * @var ModelFileTypeDAO
	 */
	protected $modelFileTypeDAO = null;
	
	protected $tmpRecord = null;
	protected $fileList = null;
	protected $fileListOldValues = null;

	protected $swfAttachmentTypes = null;
	protected $standardAttachmentTypes = null;
	protected $allAttachmentTypes = null;

	public function prepareData() {
		$this->initHelpers();
		parent::prepareData();
	}

	protected function initHelpers() {
		$allSWFAttachmentTypes = CoreConfig::get('FileUpload', 'swfUploadAttachmentTypes');
		$this->swfAttachmentTypes =
			!empty($allSWFAttachmentTypes[$this->getRecordType()])
			? $allSWFAttachmentTypes[$this->getRecordType()]
			: array();
		$allStandardAttachmentTypes = CoreConfig::get('FileUpload', 'simpleAttachmentTypes');
		$this->standardAttachmentTypes =
			!empty($allStandardAttachmentTypes[$this->getRecordType()])
			? $allStandardAttachmentTypes[$this->getRecordType()]
			: array();
		$this->allAttachmentTypes = array_merge(
			$this->swfAttachmentTypes,
			$this->standardAttachmentTypes
		);
	}

	protected function initDAO() {
		parent::initDAO();
		$this->fileDAO = new FileDAO();
		//$this->tmpRecordDAO = new TmpRecordDAO();
		//$this->modelFileTypeDAO = new ModelFileTypeDAO();
	}

	protected function initForm() {
		if (empty($this->standardAttachmentTypes)) {
			$this->form = new CoreForm('post');
		}
		else {
			$this->form = new CoreFormWithVLFs('post');
		}
	}

	protected function hasSWFUpload() {
		return (!empty($this->swfAttachmentTypes));
	}

	protected function isSWFUpload($listName) {
		return (!empty($this->swfAttachmentTypes[$listName]));
	}

	protected function isSWFUploadOrderingActive($listName) {
		return (
			!empty($this->swfAttachmentTypes[$listName])
			&& $this->swfAttachmentTypes[$listName]['maxItems'] != 1
		);
	}

	protected function initTmpRecord() {
		$tmpId = CoreServices::get('request')->getFromRequest('_tmpId');
		if (!empty($tmpId)) {
			$this->tmpRecord = $this->tmpRecordDAO->getRecordById($tmpId);
			if (empty($this->tmpRecord['id'])) {
				CoreServices2::getDB()->transactionCommit();
				CoreUtils::redirect($this->getListPageAddress());
			}
		}
		else {
			$this->tmpRecord = $this->tmpRecordDAO->getRecordTemplate();
			$this->tmpRecord['recordType'] = $this->getRecordType();
			$this->tmpRecord['_tmpRecordCreateTime'] = CoreUtils::getDateTime();
			$this->tmpRecord['_tmpRecordSessionId'] = CoreServices2::getRequest()->getSessionId();
			$this->tmpRecordDAO->save($this->tmpRecord);
		}
	}

	protected function initRecord() {
		if ($this->hasSWFUpload()) {
			$id = CoreServices::get('request')->getFromRequest('id');
			if (!empty($id)) {
				$this->record = $this->dao->getRecordById($id);
				if (empty($this->record['id'])) {
					CoreServices::get('db')->transactionCommit();
					CoreUtils::redirect($this->getListPageAddress());
				}
			}
			else {
				$this->record = $this->dao->getRecordTemplate();
				$this->initTmpRecord();
			}
			$this->initMultiselectRelations();
			$this->recordOldValues = $this->record; // clone!
			$this->checkUserPermissionsForRecord();
		}
		else {
			parent::initRecord();
		}
	}

	protected function addModelFileTypeField($listName) {
		$list = $this->modelFileTypeDAO->getListSimple();
		$options = $this->modelFileTypeDAO->modifyListForSelect(
			$list,
			'<modelFileTypeName>',
			'<choose>'
		);
		$this->form->addFieldToVLF($listName, new CoreFormFieldSelect(
			'modelFileTypeId',
			null,
			$options
		));
	}

	protected function createFormFields() {
		parent::createFormFields();
		if (!empty($this->tmpRecord['id'])) {
			$this->form->addField(
				new CoreFormFieldHidden('_tmpId', $this->tmpRecord['id'])
			);
		}
		foreach (array_keys($this->swfAttachmentTypes) as $listName) {
			if ($this->isSWFUploadOrderingActive($listName)) {
				// Typ pola jest text, ponieważ nie chcemy, żeby w widoku to pole było
				// automatycznie umieszczane na początku formularza!
				$this->form->addField(new CoreFormFieldText($listName . '_order'));
			}
		}
		foreach (array_keys($this->standardAttachmentTypes) as $listName) {
			$this->form->addFieldToVLF($listName, new CoreFormFieldHidden('id'));
			$this->form->addFieldToVLF($listName, new CoreFormFieldHidden('fileOrder'));
			$this->form->addFieldToVLF($listName, new CoreFormFieldText('fileTitle'));
			$this->addModelFileTypeField($listName);
			$this->form->addFieldToVLF($listName, new CoreFormFieldFile('_fileUpload'));
		}
	}

	protected function addFormValidators() {
		foreach ($this->standardAttachmentTypes as $listName => $listInfo) {
			$this->form->addValidator(new CoreFormValidatorVLFRecordListConsistency(
				$listName,
				$this->fileListOldValues[$listName]
			));
			if (!empty($listInfo['minItems']) || !empty($listInfo['maxItems'])) {
				$this->form->addValidator(new CoreFormValidatorVLFLength(
					$listName,
					$listInfo['minItems'],
					$listInfo['maxItems']
				));
			}
			$this->form->addValidatorForVLF(
				$listName,
				new CoreFormValidatorFileBasicCheck('_fileUpload', 'id', $listInfo['fileCategory'])
			);
			if ($listInfo['fileCategory'] == 'image') {
				$this->form->addValidatorForVLF(
					$listName,
					new CoreFormValidatorImageFile('_fileUpload')
				);
			}
			$this->form->addValidatorForVLF(
				$listName,
				new CoreFormValidatorMaxTextLength('fileTitle', 100)
			);
		}
	}

	protected function initFileListOldValues() {
		if (!empty($this->record['id'])) {
			$recordType = $this->getRecordType();
			$recordId = $this->record['id'];
		}
		elseif (!empty($this->tmpRecord['id'])) {
			$recordType = '_tmpRecord';
			$recordId = $this->tmpRecord['id'];
		}
		$this->fileListOldValues = array();
		foreach ($this->allAttachmentTypes as $listName => $fileInfo) {
			$this->fileListOldValues[$listName] = array();
			if (!empty($recordId)) {
				$files = $this->fileDAO->getListByRecord(
					$recordType,
					$recordId,
					$fileInfo['fileCategory'],
					$listName
				);
				foreach ($files as $file) {
					$this->fileListOldValues[$listName][$file['id']] = $file;
				}
			}
		}
	}

	protected function initFileList() {
		if (!empty($this->record['id'])) {
			$this->fileList = array();
			foreach (array_keys($this->standardAttachmentTypes) as $listName) {
				$this->fileList[$listName] = array();
				foreach ($this->fileListOldValues[$listName] as $record) {
					$this->fileList[$listName][$record['id']] = $record; // clone!
				}
			}
		}
	}

	/**
	 * Likely to be changed here and overwritten in subclasses too.
	 */
	protected function prepareAdditionalData() {
		//$this->initFileListOldValues();
		//$this->initFileList();
	}

	protected function setFormValuesFromRecord() {
		parent::setFormValuesFromRecord();
		foreach (array_keys($this->standardAttachmentTypes) as $listName) {
			$this->form->setVLFValuesFromRecords($listName, $this->fileList[$listName]);
		}
	}

	protected function setRecordValuesFromForm() {
		parent::setRecordValuesFromForm();
		$templateFileRecord = $this->fileDAO->getRecordTemplate();
		foreach (array_keys($this->standardAttachmentTypes) as $listName) {
			$this->fileList[$listName] = $this->form->getRecordsFromVLF(
				$listName,
				$templateFileRecord,
				$this->fileListOldValues[$listName]
			);
		}
	}

	protected function getValidationResults() {
		parent::getValidationResults();
		$this->checkSWFUploadLists();
	}

	protected function checkSWFUploadLists() {
		CoreUtils::checkConstraint(!empty($this->errorMessageContainer));
		foreach ($this->swfAttachmentTypes as $listName => $listInfo) {
			if (
				$listInfo['minItems'] > 0
				&& sizeof($this->fileListOldValues[$listName]) < $listInfo['minItems']
			) {
				$this->errorMessageContainer->addMessage('errorTooFewItems_' . $listName);
			}
		}
	}

	protected function saveFileLists() {
		foreach ($this->standardAttachmentTypes as $listName => $listInfo) {
			foreach ($this->fileList[$listName] as $index => $record) {
				if (empty($record['_inactive'])) {
					$record['recordType'] = $this->getRecordType();
					$record['recordId'] = $this->record['id'];
					$record['filePosition'] = $listName;
					$record['fileCategory'] = $listInfo['fileCategory'];
					$uploadStruct = $this->form->getField($listName . '[' . $index . '][_fileUpload]')->getValue();
					$this->fileDAO->save($record, $uploadStruct);
				}
				elseif (!empty($record['id'])) {
					$this->fileDAO->delete($record);
				}
			}
		}
	}

	protected function assignTmpRecordFilesToPermanentRecord() {
		// 1. przeniesienie wszystkich plików z tmpRecordu do rekordu docelowego;
		//    tych plików i tak na pewno nie ma w $this->fileList, trzeba je dopiero wczytać.
		$files = $this->fileDAO->getListByRecord('_tmpRecord', $this->tmpRecord['id']);
		foreach ($files as $file) {
			$file['recordType'] = $this->getRecordType();
			$file['recordId'] = $this->record['id'];
			$this->fileDAO->save($file);
		}
		// 2. usunięcie tmpRecord
		$this->tmpRecordDAO->delete($this->tmpRecord);
	}

	/**
	 * UWAGA!
	 * Z założenia to jest odporne na takie sytuacje jak dodanie lub usunięcie załączników
	 * współbieżnie przez innego użytkownika, w wyniku czego lista identyfikatorów jest
	 * nieaktualna. Na tej liście mogą niektóre identyfikatory wystepowac po klika razy
	 * a moga też byc kompletne smiecie.
	 */
	protected function updateFilesOrder() {
		foreach ($this->swfAttachmentTypes as $listName => $listInfo) {
			if ($this->isSWFUploadOrderingActive($listName)) {
				$orderFieldValue = $this->form->getField($listName . '_order')->getValue();
				if (!empty($orderFieldValue)) {
					$ids = explode(',', $orderFieldValue);
					$order = array_flip($ids);
					$files = $this->fileDAO->getListByRecord(
						$this->getRecordType(),
						$this->record['id'],
						$listInfo['fileCategory'],
						$listName
					);
					$i = 0;
					foreach ($files as $file) {
						if (array_key_exists($file['id'], $order)) {
							$file['fileOrder'] = $order[$file['id']];
						}
						else {
							$file['fileOrder'] = sizeof($files) + $i;
							$i++;
						}
						$this->fileDAO->save($file);
					}
				}
			}
		}
	}

	protected function afterSave() {
		$this->saveFileLists();
		if (!empty($this->tmpRecord['id'])) {
			$this->assignTmpRecordFilesToPermanentRecord();
		}
		$this->updateFilesOrder();
	}

//	protected function handleSaveRequest() {
//		parent::handleSaveRequest();
//		if (!$this->errorMessageContainer->isAnyErrorMessage()) {
//			$this->saveFileLists();
//		}
//	}

	public function assignDisplayVariables() {
		parent::assignDisplayVariables();
		$display = CoreServices2::getDisplay();
		$display->assign('recordType', $this->getRecordType());
		$display->assign('fileUrl', CoreServices::get('attachmentLocationManager'));
		foreach (array_keys($this->swfAttachmentTypes) as $listName) {
			if (!empty($this->fileList[$listName])) {
				$display->assign($listName, $this->fileList[$listName]);
			}
		}
		foreach (array_keys($this->allAttachmentTypes) as $listName) {
			if (!empty($this->fileListOldValues[$listName])) {
				$display->assign($listName . 'OldValues', $this->fileListOldValues[$listName]);
			}
		}
		$display->assign('maxFileSize', CoreConfig::get('CoreFiles', 'maxFileSize'));
	}
}
?>