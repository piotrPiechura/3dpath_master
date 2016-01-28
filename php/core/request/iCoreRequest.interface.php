<?php
/**
 * Classes implementing this interface give access to all request variables, no matter
 * what URL encoding or what session implementation is in use, or what is going on
 * with 'magic_quotes' and other such settings.
 * It does not support cookies, because we don't anticipate direct use of values
 * stored in cookies.
 */
interface iCoreRequest {
	/**
	 * No matter if standard PHP sessions are used, there must be some way
	 * to use named sessions.
	 * This function is NOT expected to be safe to concurrency.
	 */
	public function initSession($sessionName, $sessionId = null);

	public function getSessionId();

	public function getSessionName();

	public function isActiveSession($sessionId);

	public function isNotEmptyRequest($requestType);
	
	/**
	 * If $requestType is set to 'post', 'get' or 'session', it returns value from
	 * the respective request. If it is not set or set to null, it first checks
	 * post, then get, then session. If it is set to another value, it should throw
	 * a CoreException.
	 * Keys like 'fieldSetName[index][fieldName]' must be interpreted as nested
	 * arrays (except for session variables!).
	 */
	public function getFromRequest($key, $requestType = null);

	/**
	 * This function returns values without any additional slashes or other
	 * modifications, just as they were put into HTML form.
	 * There's an assumption that only simple values (strings) or arrays containing
	 * simple values are send by post. Nested arrays should be considered a hackig
	 * attempt and changed to empty string.
	 * Keys like 'fieldSetName[index][fieldName]' must be interpreted as nested
	 * arrays.
	 */
	public function getFromPost($key);

	/**
	 * getFromPost() doesn't make difference between unset post values and ones that
	 * are set to null. This method helps to deal with that problem.
	 * Keys like 'fieldSetName[index][fieldName]' must be interpreted as nested
	 * arrays.
	 */
	public function isSetPost($key);
	
	/**
	 * This function returns values without any additional slashes or other
	 * modifications, just as they were put into HTML form.
	 * There's an assumption that only simple values (strings) or arrays containing
	 * simple values are send by get.  Nested arrays should be considered a hackig
	 * attempt and changed to empty string.
	 * Keys like 'fieldSetName[index][fieldName]' must be interpreted as nested
	 * arrays.
	 */
	public function getFromGet($key);

	/**
	 * getFromGet() doesn't make difference between unset get values and ones that
	 * are set to null. This method helps to deal with that problem.
	 * Keys like 'fieldSetName[index][fieldName]' must be interpreted as nested
	 * arrays.
	 */
	public function isSetGet($key);

	/**
	 * This function takes all kinds of values, like objects, arrays and simple
	 * values and serializes them (if necessary).
	 * It should throw CoreException in case session was not
	 * initialized.
	 * In this case keys like 'fieldSetName[index][fieldName]' are NOT interpreted
	 * as nested arrays. This is because all objects put into session must be
	 * serialized and it wouldn't be effective to unserialize an array held in session
	 * everytime someone wants to check a single value inside the array.
	 * One can only put a complete array into session and not single elements of it,
	 * the same with reading.
	 */
	public function getFromSession($key);

	/**
	 * getFromSession() doesn't make difference between unset get values and ones that
	 * are set to null. This method helps to deal with that problem.
	 * In this case keys like 'fieldSetName[index][fieldName]' are NOT interpreted
	 * as nested arrays. This is because all objects put into session must be
	 * serialized and it wouldn't be effective to unserialize an array held in session
	 * everytime someone wants to check a single value inside the array.
	 * One can only put a complete array into session and not single elements of it,
	 * the same with reading.
	 */
	public function isSetSession($key);

	/**
	 * This function returns values that are already unserialized.
	 * It should throw CoreException in case session was not
	 * initialized.
	 * In this case keys like 'fieldSetName[index][fieldName]' are NOT interpreted
	 * as nested arrays. This is because all objects put into session must be
	 * serialized and it wouldn't be effective to unserialize an array held in session
	 * everytime someone wants to check a single value inside the array.
	 * One can only put a complete array into session and not single elements of it,
	 * the same with reading.
	 */
	public function setSession($key, $value);

	/**
	 * Returns struct like in $_FILE predefined variable.
	 * Keys like 'fieldSetName[index][fieldName]' must be interpreted as nested
	 * arrays.
	 */
	public function getFileStruct($key);

	/**
	 * Splits field names like 'fieldSetName[index][fieldName]' to parts:
	 * array (0 => 'fieldSetName', 1 => 'index', 2 => 'fieldName')
	 */
	public function getFormFieldNameSegments($fieldName);

	/**
	 * Composes form field name like 'fieldSetName[index][fieldName]' of an array of segments like:
	 * array (0 => 'fieldSetName', 1 => 'index', 2 => 'fieldName')
	 */
	public function composeFormFieldName($segments);

	/**
	 * Sends a request and returns page content or null for error;
	 * $optionalHeaders is an array containing full header texts like:
	 *     array('Expires: Thu, 19 Nov 1981 08:52:00 GMT', 'Pragma: no-cache')
	 * $getParams and $postParams are arrays like array('pName1' => pVal1, 'pName2' => 'pVal2')
	 */
	public function sendRequest($url, $timeout = null, &$optionalHeaders = null, &$getParams = null, &$postParams = null);
}
?>