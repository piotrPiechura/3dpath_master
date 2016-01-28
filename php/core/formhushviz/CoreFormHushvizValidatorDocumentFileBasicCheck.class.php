<?php
class CoreFormHushvizValidatorDocumentFileBasicCheck extends CoreFormAbstractSingleFieldValidator {
	public function __construct($fieldName) {
		$this->fieldName = $fieldName;
	}

	/**
	 * 
	 * @param unknown_type $messageManager
	 * 
	 * Ten walidator sprawdz czy rozszerzenie pliku jest dopuszczalne, natomiast nie sprawdza
	 * czy nazwa pliku jest zgodna ze zdefiniowanymi wzorcami, poniewaz moga byæ inne wzorce
	 * dla dokumentów OKE inne dla szkó³ itp.. Sprawdzene nazwy trzeba zrobiæ oddzielnie.
	 * 
	 */
	public function validate($messageManager) {
		$field = $this->form->getField($this->fieldName);
		$fieldCaption = $field->getCaption();
		$uploadStruct = $field->getValue();
		if (empty($uploadStruct)) {
			$messageManager->addMessage(
				'fileNotUploaded',
				array($this->fieldName => $fieldCaption)
			);
			return;
		}
		$filesService = CoreServices::get('files');
		if (!$filesService->checkExtension(
			substr($uploadStruct['name'], strrpos($uploadStruct['name'], '.') + 1))
		) {
			$messageManager->addMessage(
				'invalidFileType',
				array($this->fieldName => $fieldCaption)
			);
		}
		if (CoreServices::get('files')->getMaxFileSize() < $uploadStruct['size']) {
			$messageManager->addMessage(
				'fileTooBig',
				array($this->fieldName => $fieldCaption)
			);
		}
	}

	//public function modifyFieldNamesForVLF($vlfName, $index) {
	//	parent::modifyFieldNamesForVLF($vlfName, $index);
	//}
}
?>