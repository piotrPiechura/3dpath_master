<?php
class DictForCMS {
	protected static $values = array(
		'en' => array(
			'newsletterOldToDoElementsRemoved' => '@TODO: Usunięto stare zlecenia wysyłki newslettera',
			'newsletterSendFailure' => '@TODO: wysyłanie newslettera nie powiodło się',

			'garbageCollectorFailure' => 'Temporary record garbage collector error'
		)
	);

	public static function get($lang, $text) {
		return self::$values[$lang][$text];
	}
}
?>