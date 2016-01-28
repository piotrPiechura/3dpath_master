<?php
class AdminCMSListHighlight implements iCoreDisplayRecordHighlight {
	public function getValue(&$record) {
		if ($record['adminState'] == 'active') {
			return 40;  
		}
		return 0;
	}
}
?>