<?php
class CoreDebug implements iCoreDebug {
	protected $info = null;
	protected $startTime = null;

	public function __construct() {
		$this->info = array();
		$this->startTime = CoreUtils::getTimeMicroseconds();
	}

	protected function getIdByName($eventName) {
		return md5($eventName);
	}

	public function eventStart($category, $eventName) {
		$eventId = $this->getIdByName($eventName);
		if (empty($this->info[$category]['events'][$eventId]['eventName'])) {
			$this->info[$category]['events'][$eventId]['eventName'] = $eventName;
		}
		$this->info[$category]['events'][$eventId]['startTime'] =
			CoreUtils::getTimeMicroseconds() - $this->startTime;
	}

	public function eventFinish($category, $eventName) {
		$eventId = $this->getIdByName($eventName);
		if (empty($this->info[$category]['events'][$eventId]['eventName'])) {
			$this->info[$category]['events'][$eventId]['eventName'] = $eventName;
		}
		$this->info[$category]['events'][$eventId]['finishTime'] =
			CoreUtils::getTimeMicroseconds() - $this->startTime;
	}

	public function eventMessage($category, $eventName, $message) {
		$eventId = $this->getIdByName($eventName);
		if (empty($this->info[$category]['events'][$eventId]['eventName'])) {
			$this->info[$category]['events'][$eventId]['eventName'] = $eventName;
		}
		$this->info[$category]['events'][$eventId]['message'] = $message;
	}

	public function getInfo() {
		$timeLine = array();
		$info = $this->info;
		foreach ($this->info as $categoryName => $categoryInfo) {
			$totalTime = 0;
			foreach ($categoryInfo['events'] as $eventId => $eventInfo) {
				if (!empty($eventInfo['startTime'])) {
					$timeLine[$eventInfo['startTime']][] = array(
						'phase' => 'start',
						'eventId' => $eventId,
						'eventInfo' => $eventInfo
					);
				}
				if (!empty($eventInfo['finishTime'])) {
					$timeLine[$eventInfo['finishTime']][] = array(
						'phase' => 'finish',
						'eventId' => $eventId,
						'eventInfo' => $eventInfo
					);
					$startTime = !empty($eventInfo['startTime']) ? $eventInfo['startTime'] : 0;
					$totalTime += $eventInfo['finishTime'] - $startTime;
				}
			}
			$info[$categoryName]['totalTime'] = $totalTime;
			$info[$categoryName]['eventCount'] = sizeof($categoryInfo['events']);
		}
		return array(
			'info' => $info,
			'timeline' => $timeLine
		);
	}
}
?>
