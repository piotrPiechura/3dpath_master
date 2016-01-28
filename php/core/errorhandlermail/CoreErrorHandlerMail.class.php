<?php
class CoreErrorHandlerMail extends CoreErrorHandler {
	public function handleException($exception) {
		$message = 
			'Exception in file: ' . $exception->getFile() . ', Line: ' . $exception->getLine() . '; '
			. 'Message: ' . $exception->getMessage() . '; '
			. 'Backtrace: ' . $exception->getTraceAsString();
		$this->report($message);
		header("HTTP/1.0 404 Not Found");
		exit();
	}
	
	public function handleError($errno, $errstr, $errfile = null, $errline = null, $errcontext = null) {
		// autoloader doesn't work here...
		require_once(CoreConfig::get('Environment', 'applicationDir') . 'php/core/exception/CoreException.class.php');
		require_once(CoreConfig::get('Environment', 'applicationDir') . 'php/config/ConfigSettings.class.php');
		// If there is a statement preceded with '@', and it causes some warning, we don't want to throw exception...
		// Unfortunately there are some functions in PHP that trigger warnings even if nothing wrong happens.
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
			. 'Context: ' . var_export($errcontext, true);
		$this->report($message);
		header("HTTP/1.0 404 Not Found");
		exit();
	}
	
	protected function report($message) {
		// autoloader doesn't work here...
		require_once(CoreConfig::get('Environment', 'applicationDir') . 'php/core/mail/iCoreMail.interface.php');
		require_once(CoreConfig::get('Environment', 'applicationDir') . 'php/core/mailsimple/CoreMailSimple.class.php');
		// restore default handler, just in case...
		restore_error_handler();
		$to = array(CoreConfig::get('Environment', 'errorEmailRecipient'));
		$cc = null;
		$mailer = new CoreMailSimple();
		try {
			$mailer->sendPlainText(
				CoreConfig::get('Environment', 'errorEmailSender'),
				$to,
				$cc,
				CoreConfig::get('Environment', 'websiteName') . ' - Error',
				$message
			);
		}
		catch (Exception $e) {
			exit('<b>Failed to send error email!</b><pre>' . $message . '</pre>');
		}
		$this->initErrorHandler();
	}
}
?>