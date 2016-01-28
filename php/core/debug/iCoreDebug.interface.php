<?php
interface iCoreDebug {
	public function eventStart($category, $eventId);

	public function eventFinish($category, $eventId);

	public function eventMessage($category, $eventId, $message);

	public function getInfo();
}
?>
