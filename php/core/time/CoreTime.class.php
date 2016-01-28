<?php
class CoreTime {
	/**
	 * Ta funkcja zwraca na razie liczbę dni, godzin, minut, sekund
	 * pozostających do momentu podanego w parametrze. Podanie ilości
	 * miesięcy i lat to trochę gorsza sprawa (szczególnie z miesiącami
	 * jest problem koncepcyjny).
	 * @param string
	 * @return array
	 */
	public function getTimeRemaining($time) {
		$timeSeconds = strtotime($time) - strtotime(CoreUtils::getDateTime());
		if ($timeSeconds <= 0) {
			return null;
		}
		return array(
			'timeSeconds' => $timeSeconds,
			's' => $timeSeconds % 60,
			'm' => floor($timeSeconds / 60) % 60,
			'h' => floor($timeSeconds / (60 * 60)) % 24,
			'D' => floor($timeSeconds / (60 * 60 * 24))
		);
	}
}
?>