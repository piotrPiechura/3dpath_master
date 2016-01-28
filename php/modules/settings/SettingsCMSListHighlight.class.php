<?php
class SettingsCMSListHighlight implements iCoreDisplayRecordHighlight {
	public function getValue(&$record) {
		if (!empty($record['settingState'])) {
			return 40;
		}
		if (empty($record['settingState'])) {
			return 10;
		}
		return 0;
	}
}
?>