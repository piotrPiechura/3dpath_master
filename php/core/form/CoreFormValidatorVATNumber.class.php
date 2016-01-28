<?php
class CoreFormValidatorVATNumber extends CoreFormAbstractValidator {
	protected $numberFieldName = null;
	protected $countryFieldName = null;

	public function __construct($numberFieldName, $countryFieldName) {
		$this->numberFieldName = $numberFieldName;
		$this->countryFieldName = $countryFieldName;
	}

	public function validate($messageManager) {
		$numberField = $this->form->getField($this->numberFieldName);
		$number = $numberField->getValue();
		if (empty($number)) {
			return;
		}
		$countryField = $this->form->getField($this->countryFieldName);
		$countryId = $countryField->getValue();
		if (empty($countryId)) {
			// można sprawdzić prefix nr vat i na tej podstawie zgadnąć kraj.
			$country = $this->getCountryByPrefix($number);
			// jeżeli się nie uda, to
			if (empty($country['id'])) {
				$messageManager->addMessage(
					'errorCantDetermineCountryByVATNumber',
					array(
						$this->countryFieldName => $countryField->getCaption(),
						$this->numberFieldName => $numberField->getCaption()
					)
				);
				return;
			}
		}
		else {
			$country = $this->getCountryById($countryId);
			if (empty($country['id'])) {
				return; // i tak powinien się zgłosić walidator selecta
			}
		}
		$schemaFactory = new VAT_Schema_Factory();
		$schema = $schemaFactory->get($country);
		$formattedNumber = $schema->getFormattedNumber($number);
		if (empty($formattedNumber)) {
			$messageManager->addMessage(
				'errorInvalidVATNumber',
				array($this->numberFieldName => $numberField->getCaption())
			);
			return;
		}
		$countryField->setValue($country['id']);
		$numberField->setValue($formattedNumber);
	}

	public function modifyFieldNamesForVLF($vlfName, $index) {
		$this->countryFieldName = CoreServices::get('request')->composeFormFieldName(array($vlfName, $index, $this->countryFieldName));
		$this->numberFieldName = CoreServices::get('request')->composeFormFieldName(array($vlfName, $index, $this->numberFieldName));
	}

	protected function getCountryById($countryId) {
		$countryDAO = new CountryDAO();
		return $countryDAO->getRecordById($countryId);
	}

	protected function getCountryByPrefix($number) {
		$countryDAO = new CountryDAO();
		return $countryDAO->getRecordByAbbr(substr(trim($number), 0, 2));
	}
}
?>