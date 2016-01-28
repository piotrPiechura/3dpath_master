<?php
class CoreFormHushvizValidatorFileBasicCheck extends CoreFormValidatorFileBasicCheck {
	public function validate($messageManager) {
		$field = $this->form->getField($this->fieldName);
		$fieldCaption = $field->getCaption();
		$uploadStruct = $field->getValue();
		if (empty($uploadStruct)) {
			$recordId = $this->form->getField($this->idFieldName)->getValue();
			if (empty($recordId)) {
				$messageManager->addMessage(
					'fileNotUploaded',
					array($this->fieldName => $fieldCaption)
				);
			}
		}
		else {
			$filesService = CoreServices::get('files');
			if (!$filesService->checkNamePattern($uploadStruct['name'])) {
				$messageManager->addMessage(
					'invalidFileName',
					array($this->fieldName => $fieldCaption)
				);
			}
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
	}
}
?>