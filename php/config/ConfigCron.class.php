<?php
class ConfigCron extends CoreConfigAbstractConfig {
	protected function init() {
		$this->values['newsletterMailsPerExecution'] = 150;
		
		/**
		 * Po N dniach usuwamy stare zlecenia wysyłki,
		 * bo newsletter po takim czasie jest już i tak nieaktualny.
		 */
		$this->values['newsletterToDoElementMaxHours'] = 3 * 24;

		$this->values['tmpRecordOldAgeSeconds'] = 2 * ini_get('session.gc_maxlifetime');
		$this->values['tmpRecordsToDeletePerExecution'] = 500;
	}
}
?>