<?php
class ConfigEnvironment extends CoreConfigAbstractConfig {
	protected function init() {
		$this->values['websiteName'] = 'hushviz_prev';

		$this->values['dbHost'] = 'localhost';
		$this->values['dbPort'] = '';
		$this->values['dbName'] = '00494841_3path';
		$this->values['dbUser'] = '00494841_3path';
		$this->values['dbPassword'] = 'darekmankowski1980';
		
		$this->values['httpsForWebsite'] = false;
		$this->values['httpsForCMS'] = false;
		
		// Nie mo�na u�ywa� $_SERVER['HTTP_HOST'], poniewaz przy odpalaniu skrytpu z konsoli
		// (czyli te� z Crona) ta zmienna jest pusta!
		$this->values['domainName'] = $_SERVER['HTTP_HOST'];
		$this->values['urlPath'] = '/';
		$this->values['urlPrefix'] = $this->values['domainName'] . $this->values['urlPath'];
		$this->values['applicationDir'] = substr(__FILE__, 0, -strlen('php/config/ConfigEnvironment.class.php'));

		// $this->values['uploadDirDiskPath'] = $this->values['applicationDir'] . '_upload/';
		$this->values['uploadDirDiskPath'] = '/www.3dpath.com/_upload/';
		// $this->values['uploadDirHTTPPath'] = '_upload/';
		$this->values['uploadDirHTTPPath'] = 'http://3dpath2.deator.com.pl/_upload/';
		$this->values['imageCacheDirDiskPath'] = $this->values['applicationDir'] . '_tmp/imagecache/';
		$this->values['imageCacheDirHTTPPath'] = '_tmp/imagecache/';
		
		$this->values['tmpFilesDirDiskPath'] = $this->values['applicationDir'] . '_tmp/uploadmetadata/';
		
		$this->values['emailDomainName'] = str_replace('www.', '', $_SERVER['HTTP_HOST']);

		$this->values['errorLogFile'] = $this->values['applicationDir'] . '_tmp/core/errors_' . date('Y-m-d') . '.txt';
		$this->values['errorEmailSender'] = 'error_handler@' . $this->values['emailDomainName'];
		$this->values['errorEmailRecipient'] = 'marcin@deator.pl';

		$this->values['supportFormSender'] = 'support@' . $this->values['emailDomainName'];
		$this->values['supportFormRecipient'] = 'support@' . $this->values['emailDomainName'];

		// no trailing slash here:
		$this->values['htmlPurifierCacheDirDiskPath'] = $this->values['applicationDir'] . '_tmp/htmlpurifier';

		$this->values['registrationEmailSender'] = 'registration@' . $this->values['emailDomainName'];
		$this->values['passwordRecoveryEmailSender'] = 'help@' . $this->values['emailDomainName'];
		
		$this->values['newsletterSender'] = 'newsletter@hushviz.com';

		// PayPal - dev:
		$this->values['PayPalLoginUrl'] = 'https://www.sandbox.paypal.com/webscr'; // dev
		$this->values['PayPalNVPUrl'] = 'https://api-3t.sandbox.paypal.com/nvp'; // dev
		$this->values['PayPalNVPSellerUserName'] = 'paypal_osoba_api1.deator.com.pl'; // dev
		$this->values['PayPalNVPSellerPassword'] =  'LMZTE4UGYCDW85ML'; // dev
		$this->values['PayPalNVPSellerSignature'] =  'AoXHIaQByAOlGTr5oP.Sa2TYROn5AGT2.xqkiRGPQ-FSLcs33xXxhgnF'; // dev

		// PayPal - prod:
		// $this->values['PayPalLoginUrl'] = 'https://www.paypal.com/webscr'; // prod
		// $this->values['PayPalNVPUrl'] = 'https://api-3t.paypal.com/nvp'; // prod
		// $this->values['PayPalNVPSellerUserName'] = '';  // prod
		// $this->values['PayPalNVPSellerPassword'] = '';  // prod
		// $this->values['PayPalNVPSellerSignature'] = '';  // prod

		$this->values['PayPalRequestTimeout'] = 10;
		$this->values['PayPalNVPAPIVersion'] = '63.0';
                
		$this->values['OptimaPaymentXMLDir'] = $this->values['applicationDir'] . '_tmp/optima/payment/xmlinput/';
		// $this->values['OptimaCustomerXMLDir'] = $this->values['applicationDir'] . '_tmp/optima/customer/xmlinput/';
		$this->values['OptimaInvoiceDir'] = $this->values['applicationDir'] . '_tmp/optima/invoice/';

		$this->values['finfoMagicFile'] = '/usr/share/misc/magic';
	}
}
?>
