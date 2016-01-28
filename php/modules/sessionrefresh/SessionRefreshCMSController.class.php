<?php
class SessionRefreshCMSController extends SessionRefreshAbstractController {
	public function getSessionName() {
		return 'CMSSession';
		// 'defaultSession'
	}
}
?>