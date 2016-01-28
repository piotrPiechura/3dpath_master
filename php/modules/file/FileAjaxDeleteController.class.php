<?php
/**
 * W parametrach requestu musi być:
 * - view ('c' lub 'w')
 * - id (rekordu pliku)
 * - nazwa sesji = id sesji
 */
class FileAjaxDeleteController extends FileOperationAbstractController {
	/**
	 * @var FileDAO
	 */
	protected $fileDAO = null;
	/**
	 * @var CoreModelAbstractDAO
	 */
	protected $baseRecordDAO = null;
	/**
	 * @var CoreForm
	 */
	protected $form = null;
	/**
	 * @var CoreFormValidationMessageContainer
	 */
	protected $messageManager = null;
	protected $fileRecord = null;
	protected $baseRecord = null;

	// OK
	public function prepareData() {
		parent::prepareData();
		$this->messageManager = new CoreFormValidationMessageContainer();
		if (!$this->isUserPermitted()) {
			$this->messageManager->addMessage('fileDeleteErrorNoPermission');
			return;
		}
		$this->fileDAO = new FileDAO();
		CoreServices2::getDB()->transactionStart();
		$this->initFileRecord();
		if (!empty($this->fileRecord['id'])) {
			$this->initBaseRecordDAO();
			$this->initBaseRecord();
			if (!$this->hasUserPermissionsForRecord()) {
				$this->messageManager->addMessage('fileDeleteErrorNoPermission');
				return;
			}
			$this->checkDataConsistency();
			if (!$this->messageManager->isAnyErrorMessage()) {
				$this->deleteFileRecord();
			}
		}
		else {
			$this->messageManager->addMessage('fileDeleteErrorNoSuchFile');
		}
		CoreServices2::getDB()->transactionCommit();
	}

	protected function initFileRecord() {
		$this->fileRecord = $this->fileDAO->getRecordById(
			CoreServices2::getRequest()->getFromRequest('id')
		);
	}

	protected function isUserPermitted() {
		$currentUserId = CoreServices2::getAccess()->getCurrentUserId();
		return ($this->getSessionName() == 'CMSSession') && !empty($currentUserId);
	}

	protected function hasUserPermissionsForRecord() {
		CoreUtils::checkConstraint(!empty($this->baseRecordDAO));
		CoreUtils::checkConstraint(!empty($this->baseRecord['id']));
		// Sprawdzenie czy ten użytkownik ma uprawnienia do tego rekordu
		// Jest użytkownikiem CMS, więc ma.
		return true;
	}

	protected function initBaseRecordDAO() {
		$recordType = $this->fileRecord['recordType'];
		$daoClass =
			($recordType == '_tmpRecord')
			? 'TmpRecordDAO'
			: strtoupper(substr($recordType, 0, 1)) . substr($recordType, 1) . 'DAO';
		$this->baseRecordDAO = new $daoClass();
	}

	protected function initBaseRecord() {
		$this->baseRecord = $this->baseRecordDAO->getRecordById($this->fileRecord['recordId']);
		CoreUtils::checkConstraint(!empty($this->baseRecord['id']));
	}


	protected function checkDataConsistency() {
		$attachmentTypes = CoreConfig::get('FileUpload', 'swfUploadAttachmentTypes');
		$fileCategory = $this->fileRecord['fileCategory'];
		$recordType = $this->fileRecord['recordType'];
		if ($recordType == '_tmpRecord') {
			return;
		}
		$filePosition = $this->fileRecord['filePosition'];
		CoreUtils::checkConstraint(is_array($attachmentTypes[$recordType][$filePosition]));
		$minItems = $attachmentTypes[$recordType][$filePosition]['minItems'];
		if (!empty($minItems)) {
			$currentItems = $this->fileDAO->getCountByRecord(
				$recordType,
				$this->baseRecord['id'],
				$fileCategory,
				$filePosition
			);
			if ($currentItems - 1 < $minItems) {
				$this->messageManager->addMessage('fileDeleteErrorTooFewAttachments');
				return;
			}
		}
	}

	/**
	 * Ta funkcja nie poprawia fileOrder w pozostałych plikach,
	 * dziury nam tak bardzo nie wadzą
	 */
	protected function deleteFileRecord() {
		$this->fileDAO->delete($this->fileRecord);
	}

	public function sendHeaders() {
		header('Content-type: application/json');
	}

	protected function displayOK() {
		$returnValue = json_encode(array(
			'status' => 'OK'
		));
		echo($returnValue);
	}
}
?>