<?php
class CoreErrorHandler {
	protected function initErrorHandler() {
		$callback = array($this, 'handleError');
		set_error_handler($callback, E_ALL | E_STRICT | E_WARNING);
	}

	protected function getErrorReportingInfo() {
		$a = array(
			'E_NOTICE' => E_NOTICE,
			'E_USER_NOTICE' => E_USER_NOTICE,
			'E_WARNING' => E_WARNING,
			'E_USER_WARNING' => E_USER_WARNING,
			'E_ERROR' => E_ERROR,
			'E_USER_ERROR' => E_USER_ERROR,
			'E_CORE_ERROR' => E_CORE_ERROR,
			'E_COMPILE_ERROR' => E_COMPILE_ERROR,
			'E_STRICT' => E_STRICT,
			'E_PARSE' => E_PARSE,
			'E_CORE_WARNING' => E_CORE_WARNING,
			'E_COMPILE_WARNING' => E_COMPILE_WARNING,
			'E_RECOVERABLE_ERROR' => E_RECOVERABLE_ERROR,
			//'E_DEPRECATED' => E_DEPRECATED,
			//'E_USER_DEPRECATED' => E_USER_DEPRECATED
		);
		$e = error_reporting();
		$res = 'error-reporting: ' . $e . '<br>';
		$res .= 'E_ALL: ' . E_ALL . '<br>';
		$res .= '<hr>';
		foreach ($a as $k => $v) {
			if (($v & $e) > 0) {$res .= '<b>';}
			$res .= $k . ': ' . $v . ' (' . ($v & $e) / $v . ')';
			if (($v & $e) > 0) {$res .= '</b>';}
			$res .= '<br>';
		}
		$res .= '<hr>';
		return $res;
	}

	public function init() {
		ini_set('error_log', CoreConfig::get('Environment', 'errorLogFile'));
		ini_set('log_errors', 1);
		// $errorReportingInfo = $this->getErrorReportingInfo();
		// echo($errorReportingInfo);
		$this->initErrorHandler();
	}

	public function handleException($exception) {
		$message = 
			'Exception in file: ' . $exception->getFile() . ', Line: ' . $exception->getLine() . '; '
			. 'Message: ' . $exception->getMessage() . '; '
			. 'Backtrace: ' . $exception->getTraceAsString();
		exit('<pre>' . $message . '</pre>');
	}
	
	public function handleError($errno, $errstr, $errfile = null, $errline = null, $errcontext = null) {
		// autoloader doesn't work here...
		require_once(CoreConfig::get('Environment', 'applicationDir') . 'php/config/ConfigSettings.class.php');
		// If there is a statement preceded with '@', and it causes some warning,
		// we don't want to throw exception, because:
		// Unfortunately there are some functions in PHP that trigger warnings
		// even if nothing wrong happens.
		if (
			ini_get('error_reporting') == 0
			|| in_array($errno, CoreConfig::get('Settings', 'ignoredErrorLevels'))
		) {
			return true;
		}
		
		$message =
			'Error in file: ' . $errfile . ', Line: ' . $errline . '; '
			. 'Code: ' . $errno . '; '
			. 'Message: ' . $errstr . '; '
			. 'Context: ' . serialize($errcontext);
			// var_export($errcontext, true) byłoby fajniejsze ale niestety
			// wysypuje się przy tablicach o kilku poziomach zagnieżdżenia;
			// w szczególnosci dzieje się tak jeśli błąd jest w Smarty.
		exit('<pre>' . $message . '</pre>');
	}
}
?>
