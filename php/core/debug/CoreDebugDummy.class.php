<?php
class CoreDebugDummy implements iCoreDebug {
	public function __construct() {}

	public function eventStart($category, $eventId) {}

	public function eventFinish($category, $eventId) {}

	public function eventMessage($category, $eventId, $message) {}

	public function getInfo() {
		return null;
	}
}
?>
