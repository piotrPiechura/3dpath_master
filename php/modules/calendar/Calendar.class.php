<?php
class Calendar {
	public function addWeek($date) {
		return date('Y-m-d', strtotime($date . ' +7 days'));
	}

	public function addDateDifference($base, $start, $final) {
		return date('Y-m-d', strtotime($base . ' +' . (strtotime($final) - strtotime($start)) . ' seconds'));
	}
	
	function getMondayOfTheWeek($date) {
		$timestamp = strtotime($date);
		$weekDay = date('N', $timestamp);
		$diff = $weekDay - 1;
		$dateFormatted = date('Y-m-d', $timestamp);
		if (!$diff) {
			return $dateFormatted;
		}
		return date('Y-m-d', strtotime($dateFormatted . ' -' . (24 * $diff - 2) . ' hours'));
	}

	public function getSundayOfTheWeek($date, $daysOffset = null) {
		$base = $date;
		if ($daysOffset > 0) {
			$base .= ' +' . $daysOffset . ' days';
		}
		elseif ($daysOffset < 0) {
			$base .= ' ' . $daysOffset . ' days';
		}
		$timestamp = strtotime($base);
		$weekDay = date('N', $timestamp);
		$diff = 7 - $weekDay;
		$dateFormatted = date('Y-m-d', $timestamp);
		if (!$diff) {
			return $dateFormatted;
		}
		return date('Y-m-d', strtotime($dateFormatted . ' +' . (24 * $diff + 2) . ' hours'));
	}

	public function getFirstDayOfTheMonth($date) {
		return substr($date, 0, 8) . '01';
	}

	public function getFirstDayOfPreviousMonth($date) {
		return date('Y-m-d', strtotime($this->getFirstDayOfTheMonth($date) . ' 02:00:00 -1 month'));
	}

	public function getLastDayOfPreviousMonth($date) {
		return date('Y-m-d', strtotime($this->getFirstDayOfTheMonth($date) . ' 02:00:00 -1 day'));
	}

	public function getYearWeek($date) {
		return date('Y/W', strtotime($date));
	}

	public function getTimeFromDateAndHour($date, $hour) {
		return date('Y-m-d H:i:s', strtotime($date . ' ' . $hour . ':00'));
	}
	
	public function getDateTimeXHoursFromNow($hours) {
		return date('Y-m-d H:i:s', strtotime('now ' . $hours . ' hours'));
	}

	public function addSeconds($dateTime, $seconds) {
		return date('Y-m-d H:i:s', strtotime($dateTime) + $seconds);
	}
}
?>