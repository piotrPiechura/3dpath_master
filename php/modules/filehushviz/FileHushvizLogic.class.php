<?php
class FileHushvizLogic {
	// obiekt logiki zdecydowanie powinien byc immutable!
	// skoro już nie jest, to chociaż zrobimy tak, żeby było wiadomo
	// którego obiektu download dotyczą informacje przechowywane w atrybutach
	protected $errorMessages = null;
	protected $messages = null;
	protected $freeModelOfTheMonth = false;

	public function __construct() {
		$this->errorMessages = array();
		$this->messages = array();
		$this->freeModelOfTheMonth = array();
	}

	public function isFreeModelOfTheMonth($modelId) {
		CoreUtils::checkConstraint(!empty($modelId));
		CoreUtils::checkConstraint(isset($this->freeModelsOfTheMonth[$modelId]));
		return $this->freeModelsOfTheMonth[$modelId];
	}

	public function getErrorMessage($recordId) {
		CoreUtils::checkConstraint(!empty($recordId));
		CoreUtils::checkConstraint(isset($this->errorMessages[$recordId]));
		return $this->errorMessages[$recordId];
	}

	public function getMessage($recordId) {
		CoreUtils::checkConstraint(!empty($recordId));
		CoreUtils::checkConstraint(isset($this->messages[$recordId]));
		return $this->messages[$recordId];
	}

	public function isProtectedFile(&$record) {
		return
			$record['recordType'] == 'model'
			&& $record['filePosition'] == 'main';
	}

	public function isCurrentUserAllowed(&$record, $updateDownloadObject) {
		CoreUtils::checkConstraint($record['id']);
		CoreUtils::checkConstraint($record['recordId']);
		$recordId = $record['id'];
		if (!$this->isProtectedFile($record)) {
			return true;
		}

		$this->freeModelsOfTheMonth[$record['recordId']] = false;
		$this->setMessage($recordId, false);
		$this->setErrorMessage($recordId, false);
		$sessionName = CoreServices2::getRequest()->getSessionName();
		if (empty($sessionName)) {
			$this->setErrorMessage($recordId, 'emptySessionDownloadError');
			return false;
		}

		$currentUser = CoreServices2::getAccess()->getCurrentUserData();
		if (empty($currentUser['id'])) {
			$this->setErrorMessage($recordId, 'noUserDownloadError');
			return false;
		}
		if ($sessionName == 'CMSSession') {
			return $this->isAdminAllowed($record);
		}

		$year = date("Y");
		$month = date("n");
		$modelOfTheMonthDAO = new ModelOfTheMonthDAO();
		$modelOfTheMonthInfoRecord = $modelOfTheMonthDAO->getFreeModelOfTheMonth($year, $month);
		if ( // Jeżeli jest to darmowy model...
			!empty($modelOfTheMonthInfoRecord['modelId'])
			&& $modelOfTheMonthInfoRecord['modelId'] == $record['recordId']
		) {
			$this->freeModelsOfTheMonth[$record['recordId']] = true;
			if(!empty($updateDownloadObject)) {
				$downloadDAO = new DownloadDAO();
				$downloadRecord = $downloadDAO->getRecordTemplate();
				$modelDAO = new ModelDAO();
				$modelRecord = $modelDAO->getRecordById($modelOfTheMonthInfoRecord['modelId']);
				CoreUtils::checkConstraint(!empty($modelRecord['id']));
				$downloadRecord['userId'] = $currentUser['id'];
				$downloadRecord['modelId'] = $modelRecord['id'];
				$downloadRecord['fileId'] = $record['id'];
				$downloadRecord['downloadStartTime'] = CoreUtils::getDateTime();
				$downloadRecord['downloadAttempts'] = 0;
				$downloadRecord['downloadCreditsCost'] = 0;
				$downloadRecord['downloadModelName'] = $modelRecord['modelName'];
				$downloadRecord['downloadFileTypeName'] = $record['modelFileTypeName'];
				$downloadRecord['downloadPaid'] = 0;
				$downloadRecord['downloadFree'] = 1;
				$downloadDAO->save($downloadRecord);
			}
			return true;
		}

		$downloadLogic = new DownloadLogic();
		if (!empty($updateDownloadObject)) {
			$result = $downloadLogic->checkAndUpdateDownloadObject($currentUser['id'], $record);
		}
		else {
			$result = $downloadLogic->checkDownloadObject($currentUser['id'], $record);
		}
		if(!$result) {
			$this->setErrorMessage($recordId, $downloadLogic->getErrorMessage());
		}
		else {
			$this->setMessage($recordId, $downloadLogic->getMessage());
		}
		return $result;
	}

	// ---------------------------------------------------

	protected function setErrorMessage($recordId, $message) {
		CoreUtils::checkConstraint(!empty($recordId));
		$this->errorMessages[$recordId] = (!empty($message) ? $message : false);
		CoreUtils::checkConstraint(isset($this->errorMessages[$recordId]));
	}

	protected function setMessage($recordId, $message) {
		CoreUtils::checkConstraint(!empty($recordId));
		$this->messages[$recordId] = (!empty($message) ? $message : false);
		CoreUtils::checkConstraint(isset($this->messages[$recordId]));
	}

	protected function isAdminAllowed(&$record) {
		return True;
	}
}
?>
