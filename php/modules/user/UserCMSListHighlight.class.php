<?php
class UserCMSListHighlight implements iCoreDisplayRecordHighlight {
	public function getValue(&$record) {
		if ($record['userState'] == 'active') {
			return 40;
		}
		if ($record['userState'] == 'blocked') {
			return 10;
		}
		return 0;
	}
}
?>