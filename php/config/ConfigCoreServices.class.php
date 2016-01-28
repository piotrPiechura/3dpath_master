<?php
/**
 * Here are defined all services that can possibly be available in a given application.
 * Only services defined here can be registered in CoreServices.
 */
class ConfigCoreServices {
	public function get($serviceName) {
		switch ($serviceName) {
			case 'url':
				return new CoreUrlStandard();
				// return new CoreUrlFriendlyLinks1();
			case 'request':
				return new CoreRequestStandard();
			case 'db':
				return new CoreDBMySQL(
					CoreConfig::get('Environment', 'dbHost'),
					CoreConfig::get('Environment', 'dbPort'),
					CoreConfig::get('Environment', 'dbUser'),
					CoreConfig::get('Environment', 'dbPassword'),
					CoreConfig::get('Environment', 'dbName')
				);
			case 'display':
				return new CoreDisplaySmarty();
			case 'lang':
				return new CoreLang();
			case 'access':
				return new CoreAccessVariant1();
			case 'modules':
				return new CoreControllerManager();
			case 'files':
				return new CoreFileManager();
			// @TODO: ?
			// case 'files1':
			//	return new CoreFileHushvizManager();
			case 'images':
				return new CoreFileImageHandler();
			case 'attachmentLocationManager':
				return new FileHushvizUrlManager();
			case 'websiteMenuManager':
				return new SubpageUrlManager();
				// return new SubpageUrlManagerFriendlyLinks1();
			case 'mail':
				return new CoreMailSimple();
			case 'paymentRelationLogic':
				return new CreditsPackagePaymentRelationLogic();
			case 'paymentProviderInterface':
				return new PayPal_EC();
				// return new PaymentDummy();
			case 'errorHandler':
				return new CoreErrorHandler();
				//return new CoreErrorHandlerMail();
		}
		throw new CoreException('Service name not registered: \'' . $serviceName . '\'.');
	}
}
?>