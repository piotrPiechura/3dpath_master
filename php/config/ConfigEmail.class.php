<?php
class ConfigEmail extends CoreConfigAbstractConfig {
	protected function init() {
		$this->values['mailsPerExecution'] = 500;
		
		/**
		 * Po 7 dniach usuwamy stare zlecenia wysyłki,
		 * bo informacja po takim czasie jest już i tak nieaktualna.
		 */
		$this->values['toDoElementMaxHours'] = 7 * 24;
	}
}
?>