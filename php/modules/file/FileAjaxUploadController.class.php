<?php
class FileAjaxUploadController extends FileOperationAbstractController {
	/**
	 * @var FileDAO
	 */
	protected $fileDAO = null;
	/**
	 * @var CoreForm
	 */
	protected $form = null;
	/**
	 * @var CoreFormValidationMessageContainer
	 */
	protected $messageManager = null;
	protected $fileRecord = null;
	protected $fileLinkHTML = null;

	// OK
	public function prepareData() {
		parent::prepareData();
		if (!$this->isUserPermitted()) {
			$this->messageManager = new CoreFormValidationMessageContainer();
			$this->messageManager->addMessage('fileUploadErrorNoPermission');
			return;
		}
		$this->initForm();
		$this->createFormFields();
		$this->setFieldValuesFromRequest();
		$this->fileDAO = new FileDAO();
		CoreServices2::getDB()->transactionStart();
		$this->validateFields();
		if (!$this->messageManager->isAnyErrorMessage()) {
			$this->saveFileRecord();
		}
		CoreServices2::getDB()->transactionCommit();
		if (!empty($this->fileRecord['id'])) {
			$this->initFileLinkHTML();
		}
	}

	protected function initForm() {
		$this->form = new CoreForm('post');
	}

	protected function createFormFields() {
		$this->form->addField(new CoreFormFieldFile('_fileUpload'));
		$this->form->addField(new CoreFormFieldText('id'));
		$this->form->addField(new CoreFormFieldText('_tmpId'));
		$this->form->addField(new CoreFormFieldText('fileCategory'));
		$this->form->addField(new CoreFormFieldText('filePosition'));
		$this->form->addField(new CoreFormFieldText('recordType'));
		$this->form->addField(new CoreFormFieldText('image_width'));
		$this->form->addField(new CoreFormFieldText('image_height'));
		$this->form->addField(new CoreFormFieldText('image_ignoreProportions'));
		$this->form->addField(new CoreFormFieldText('image_crop'));
		$this->form->addField(new CoreFormFieldText('image_backgroundColor'));
	}

	// OK
	protected function setFieldValuesFromRequest() {
		$this->form->setFieldValuesFromRequest();
	}

	protected function isValidBaseRecord($recordType, &$record) {
		if (
			$recordType == 'subpage'
			&& (
				empty($record['subpageModule'])
				|| $record['subpageModule'] != 'Subpage'
				|| empty($record['subpageMode'])
				|| $record['subpageMode'] != 'Website'
			)
		) {
			return False;
		}
		return True;
	}

	protected function isUserPermitted() {
		return true;
		// Workaround...
		// Flash nie wysyła cookie, trzeba słać urlem, ale to i tak nie działa, ponieważ:
		// session_name() == 'hushviz_prev_CMSSession'
		// oraz
		// !empty($_GET['hushviz_prev_CMSSession'])
		// natomiast
		// session_id() != $_GET['hushviz_prev_CMSSession']
		// Magia skarpet.
		//
		//$currentUserId = CoreServices2::getAccess()->getCurrentUserId();
		//return ($this->getSessionName() == 'CMSSession') && !empty($currentUserId);
	}

	protected function hasUserPermissionsForRecord($recordType, &$record) {
		// Jest użytkownikiem CMS, więc zakładamy że ma
		return true;
	}

	protected function getBaseRecord() {
		$recordType = $this->form->getField('recordType')->getValue();
		$tmpId = $this->form->getField('_tmpId')->getValue();
		if (!empty($tmpId)) {
			$dao = new TmpRecordDAO();
			$record = $dao->getRecordById($tmpId);
			if (empty($record['id']) || $record['recordType'] != $recordType) {
				return null;
			}
			return $record;
		}
		else {
			$daoClass = strtoupper(substr($recordType, 0, 1)) . substr($recordType, 1) . 'DAO';
			$dao = new $daoClass();
			$id = $this->form->getField('id')->getValue();
			$record = $dao->getRecordById($id);
			if (
				empty($record['id'])
				|| !$this->isValidBaseRecord($recordType, $record)
			) {
				return null;
			}
			return $record;
		}
	}

	protected function checkBasicValidation() {
		$fileCategory = $this->form->getField('fileCategory')->getValue();
		$this->form->addValidator(new CoreFormValidatorFileBasicCheck('_fileUpload', null, $fileCategory));
		$this->form->addValidator(new CoreFormValidatorNotEmptyFieldset(array('id', '_tmpId')));
		$this->form->addValidator(new CoreFormValidatorFieldsetXOr(array('id', '_tmpId')));
		$this->messageManager = $this->form->getValidationResults();
	}

	protected function checkDataConsistency() {
		$attachmentTypes = CoreConfig::get('FileUpload', 'swfUploadAttachmentTypes');
		$fileCategory = $this->form->getField('fileCategory')->getValue();
		$recordType = $this->form->getField('recordType')->getValue();
		CoreUtils::checkConstraint($recordType != '_tmpRecord');
		$filePosition = $this->form->getField('filePosition')->getValue();
		$record = $this->getBaseRecord();
		if (empty($record)) {
			$this->messageManager->addMessage('fileUploadErrorInvalidBaseRecordId');
			return;
		}
		if (!$this->hasUserPermissionsForRecord($recordType, $record)) {
			$this->messageManager->addMessage('fileUploadErrorNoPermission');
			return;
		}
		if (!array_key_exists($recordType, $attachmentTypes)) {
			$this->messageManager->addMessage('fileUploadErrorInvalidRecordType');
			return;
		}
		if (!array_key_exists($filePosition, $attachmentTypes[$recordType])) {
			$this->messageManager->addMessage('fileUploadErrorInvalidFilePosition');
			return;
		}
		if ($fileCategory != $attachmentTypes[$recordType][$filePosition]['fileCategory']) {
			$this->messageManager->addMessage('fileUploadErrorWrongCategory');
			return;
		}
		$maxItems = $attachmentTypes[$recordType][$filePosition]['maxItems'];

		if (!empty($maxItems)) {
			$currentItems = $this->fileDAO->getCountByRecord(
				$recordType,
				$record['id'],
				$fileCategory,
				$filePosition
			);
			if ($currentItems + 1 > $maxItems) {
				$this->messageManager->addMessage('fileUploadErrorTooManyAttachments');
				return;
			}
		}
	}

	protected function validateFields() {
		$this->checkBasicValidation();
		if (!$this->messageManager->isAnyErrorMessage()) {
			$this->checkDataConsistency();
		}
	}

	// OK
	protected function saveFileRecord() {
		$this->fileRecord = $this->fileDAO->getRecordTemplate();
		$this->form->setRecordValuesFromFields($this->fileRecord);
		$this->fileRecord['id'] = null;
		$recordId = $this->form->getField('id')->getValue();
		$this->fileRecord['recordId'] =
			!empty($recordId)
			? $recordId
			: $this->form->getField('_tmpId')->getValue();
		$this->fileRecord['recordType'] =
			!empty($recordId)
			? $this->form->getField('recordType')->getValue()
			: '_tmpRecord';
		$this->fileRecord['fileUpdateTime'] = CoreUtils::getDateTime();
		$maxOrder = $this->fileDAO->getMaxOrderByRecord(
			$this->fileRecord['recordType'],
			$this->fileRecord['recordId'],
			$this->fileRecord['fileCategory'],
			$this->fileRecord['filePosition']
		);
		// numerowanie od 0!
		$this->fileRecord['fileOrder'] = $maxOrder + 1;
		$uploadStruct = $this->form->getField('_fileUpload')->getValue();
		$this->fileDAO->save($this->fileRecord, $uploadStruct);
	}

	// OK
	protected function initFileLinkHTML() {
		if ($this->fileRecord['fileCategory'] == 'image') {
			$this->fileLinkHTML = CoreServices2::getAttachmentLocationManager()->getImageLinkHTML(
				$this->fileRecord,
				$this->form->getField('image_width')->getValue(),
				$this->form->getField('image_height')->getValue(),
				$this->form->getField('image_ignoreProportions')->getValue(),
				$this->form->getField('image_crop')->getValue(),
				$this->form->getField('image_backgroundColor')->getValue()
			);
		}
		else {
			$this->fileLinkHTML = CoreServices2::getAttachmentLocationManager()->getLinkHTML(
				$this->fileRecord
			);
		}
	}

	public function sendHeaders() {
		header('Content-type: application/json');
	}

	public function displayOK() {
		$returnValue = json_encode(array(
			'status' => 'OK',
			'data' => array(
				'id' => $this->fileRecord['id'],
				'url' => $this->fileLinkHTML
			)
		));
		echo($returnValue);
	}
}
?>