<?php
/**
 * Ta klasa jest tylko fasadą (a może adapterem?) dla CoreServices.
 * Jej jedyny sens istnienia stanowią komentarze przy metodach, pozwalające IDE na ustalenie
 * interfejsu obiektów zwracanych przez poszczególne metody.
 */
class CoreServices2 {
	/**
	 * @return iCoreDB
	 */
	public static function getDB($index = '') {
		return CoreServices::get('db' . $index);
	}

	/**
	 * @return CoreUrlAbstractUrl
	 */
	public static function getUrl($index = '') {
		return CoreServices::get('url' . $index);
	}

	/**
	 * @return iCoreRequest
	 */
	public static function getRequest($index = '') {
		return CoreServices::get('request' . $index);
	}

	/**
	 * @return CoreDisplayAbstractEngine
	 */
	public static function getDisplay($index = '') {
		return CoreServices::get('display' . $index);
	}

	/**
	 * @return iCoreAccess
	 */
	public static function getAccess($index = '') {
		return CoreServices::get('access' . $index);
	}

	/**
	 * @return CoreControllerManager
	 */
	public static function getModules($index = '') {
		return CoreServices::get('modules' . $index);
	}

	/**
	 * @return CoreFileManager
	 */
	public static function getFiles($index = '') {
		return CoreServices::get('files' . $index);
	}

	/**
	 * @return iCoreMail
	 */
	public static function getMail($index = '') {
		return CoreServices::get('mail' . $index);
	}

	/**
	 * @return iCoreFileLocationManager
	 */
	public static function getAttachmentLocationManager($index = '') {
		return CoreServices::get('attachmentLocationManager' . $index);
	}

	/**
	 * @return iPaymentProviderInterface
	 */
	public static function getPaymentProviderInterface($index = '') {
		return CoreServices::get('paymentProviderInterface' . $index);
	}

	/**
	 * @return iPaymentRelationLogic
	 */
	public static function getPaymentRelationLogic($index = '') {
		return CoreServices::get('paymentRelationLogic' . $index);
	}

	/**
	 * @return CoreFileImageHandler
	 */
	public static function getImages($index = '') {
		return CoreServices::get('images' . $index);
	}

	/**
	 * @return CoreLang
	 */
	public static function getLang($index = '') {
		return CoreServices::get('lang' . $index);
	}

	// 'websiteMenuManager'
}
?>
