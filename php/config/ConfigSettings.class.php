<?php
class ConfigSettings extends CoreConfigAbstractConfig {
	protected function init() {
		$this->values['ignoredErrorLevels'] = array(
			// E_ERROR,
			// E_WARNING,
			// E_PARSE,
			// E_NOTICE, // @TODO: odkomentować w wersji prod.
			// E_CORE_ERROR,
			// E_CORE_WARNING,
			// E_COMPILE_ERROR,
			// E_COMPILE_WARNING,
			// E_USER_ERROR,
			// E_USER_WARNING,
			E_USER_NOTICE,
			// E_STRICT,
			E_RECOVERABLE_ERROR
			// E_DEPRECATED,
			// E_USER_DEPRECATED,
			// E_ALL
		);

		$this->values['adminMaxLoginAttempts'] = 3;
		$this->values['adminAccountBlockSeconds'] = 15 * 60;
		$this->values['userMaxLoginAttempts'] = 20;
		$this->values['userAccountBlockSeconds'] = 2 * 60 * 60;

		$this->values['maxDownloadsInTransaction'] = 5;
		$this->values['maxDownloadDelayInTransaction'] = 60 * 48; // minuty
		$this->values['removeModelFromLightboxAfterDownload'] = 1;

		// @TODO: chwilowo sprawdzanie wg autora jest zakomentowane, ponieważ
		// autorzy raczej nie są prezentowani; łaczenie po niewidocznej informacji
		// o autorze powoduje że użytkownik czuje się zagubiony
		// $this->values['modelSimilarityTheSameAuthorWeight'] = 5;
		$this->values['modelSimilarityTheSameTypeWeight'] = 1;
		$this->values['modelSimilarityCommonCategory'] = 2;
		$this->values['modelSimilarityCommonTag'] = 9;

		$this->values['clipboardCapacity'] = 250;

		// ilość dni przez jakie ważne są kredyty,
		// jeśli w takim czasie nie zostanie wykupiony kolejny pakiet
		// to wszystkie kredyty przepadają
		$this->values['creditsPackageDurability'] = 365;

		// ilość znalezionych elementów forum wyświetlanych na jednej stronie
		$this->values['forumSearchRecordsOnPage'] = 25;

		$this->values['newsletterResignationLinkDummy'] = '<!--newsletterResignationURL-->';

		// PayPal
		$this->values['paymentCurrency'] = 'EUR';
		$this->values['PayPalNVPRequestTimeout'] = 10;

		// Polska stawka VAT na pakiety kredytów
		$this->values['VATPLPercent'] = 23;
	}
}
?>