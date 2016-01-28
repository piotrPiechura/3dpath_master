<?php
class CoreFormValidatorImageFile extends CoreFormAbstractSingleFieldValidator {
	public function __construct($fieldName) {
		parent::__construct($fieldName);
	}

	public function validate($messageManager) {
		$field = $this->form->getField($this->fieldName);
		$uploadStruct = $field->getValue();
		$allowedMimeTypes = CoreConfig::get('CoreFiles', 'allowedMimeTypes');
		if (
			!empty($uploadStruct)
			&& (
				!in_array($uploadStruct['type'], $allowedMimeTypes['image'])
				|| !CoreServices::get('images')->checkImageFileContent($uploadStruct['type'], $uploadStruct['tmp_name'])
			)
		) {
			$messageManager->addMessage(
				'fileTypeCheckFailed',
				array($this->fieldName => $field->getCaption())
			);			
		}
	}
}
?>