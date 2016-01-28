<?php
class TmpRecordGarbageCollector {
	/**
	 * Usuwa te rekordy z tabeli _tmpRecord, dla których sesja już na pewno wygasła.
	 * Usuwa też przypisane do tych rekordów pliki.
	 */
	public function clean() {
		$calendar = new Calendar();
		$time = $calendar->addSeconds(
			CoreUtils::getDateTime(),
			-CoreConfig::get('Cron', 'tmpRecordOldAgeSeconds')
		);
		$tmpRecordDAO = new TmpRecordDAO();
		$oldRecords = $tmpRecordDAO->getOldRecords(
			$time,
			CoreConfig::get('Cron', 'tmpRecordsToDeletePerExecution')
		);
		$fileDAO = new FileDAO();
		foreach ($oldRecords as $record) {
			if ($this->isForDeletion($record)) {
				$files = $fileDAO->getListByRecord('_tmpRecord', $record['id']);
				foreach ($files as $file) {
					$fileDAO->delete($file);
				}
				$tmpRecordDAO->delete($record);
			}
		}
	}

	protected function isForDeletion(&$tmpRecord) {
		return !CoreServices2::getRequest()->isActiveSession($tmpRecord['_tmpRecordSessionId']);
	}
}
?>
