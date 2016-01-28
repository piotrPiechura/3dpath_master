<?php
class CoreTestTime {
	static protected $timePoints = null;
	static protected $index = 0;
	
	static public function add($pointName = '') {
		if (self::$index == 0) {
			self::$timePoints = array();
		}
		self::$timePoints[self::$index] = array();
		$currentTime = CoreUtils::getTimeMicroseconds();
		self::$timePoints[self::$index]['diff'] = 
			self::$index != 0
			? $currentTime - self::$timePoints[self::$index - 1]['time']
			: 0;
		self::$timePoints[self::$index]['time'] = $currentTime;
		self::$timePoints[self::$index]['name'] = $pointName;
		self::$index++;
	}
	
	static public function showHTML() {
		if (self::$index) {
			echo('<table cellpadding="0" cellspacing="0" style="position:absolute;left:0px;top:0px;z-index:999;">');
			foreach (self::$timePoints as $point) {
				echo('<tr><td style="padding:3px;background-color:white;color:black;text-align:left;">' . $point['name'] . '</td><td style="padding:3px;background-color:white;color:black;text-align:right;"> + ' . $point['diff'] . '</td><td style="padding:3px;background-color:white;color:black;text-align:right;"> = ' . $point['time'] . '</td></tr>');
			}
			echo('</table>');
		}
	}
}
?>