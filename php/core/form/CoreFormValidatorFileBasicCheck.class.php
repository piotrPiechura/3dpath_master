<?php
class CoreFormValidatorFileBasicCheck extends CoreFormAbstractSingleFieldValidator {
	protected $fileCategory = null;
	protected $idFieldName = null;
	
	public function __construct($fieldName, $idFieldName, $fileCategory) {
		parent::__construct($fieldName);
		$this->idFieldName = $idFieldName;
		$this->fileCategory = $fileCategory;
	}

	public function validate($messageManager) {
		$field = $this->form->getField($this->fieldName);
		$fieldCaption = $field->getCaption();
		$uploadStruct = $field->getValue();
		if (empty($uploadStruct)) {
			$recordId =
				empty($this->idFieldName)
				? null
				: $this->form->getField($this->idFieldName)->getValue();
			if (empty($recordId)) {
				$messageManager->addMessage(
					'fileNotUploaded',
					array($this->fieldName => $fieldCaption)
				);
			}
		}
		elseif (!empty($uploadStruct['error']) && $uploadStruct['error'] != UPLOAD_ERR_OK) {
			$message = $this->decodePHPUploadError($uploadStruct['error']);
			$messageManager->addMessage($message, array($this->fieldName => $fieldCaption));
		}
		else {
			$extension = CoreServices2::getFiles()->checkTypeAndGetExtension(
				$uploadStruct,
				$this->fileCategory
			);
			if (empty($extension)) {
				$messageManager->addMessage(
					'invalidFileType_' . $this->fileCategory,
					array($this->fieldName => $fieldCaption)
				);
			}
			if (CoreServices2::getFiles()->getMaxFileSize() < $uploadStruct['size']) {
				$messageManager->addMessage(
					'fileTooBig',
					array($this->fieldName => $fieldCaption)
				);
			}
		}
	}

	protected function decodePHPUploadError($errorCode) {
		CoreUtils::checkConstraint($errorCode != UPLOAD_ERR_OK);
		switch ($errorCode) {
			case UPLOAD_ERR_INI_SIZE:
			case UPLOAD_ERR_FORM_SIZE:
				return 'fileTooBig';
			case UPLOAD_ERR_PARTIAL:
				return 'fileUploadedPartially';
			case UPLOAD_ERR_NO_FILE:
				return 'fileNotUploaded';
			case UPLOAD_ERR_NO_TMP_DIR:
			case UPLOAD_ERR_NO_TMP_DIR:
			case UPLOAD_ERR_EXTENSION:
			default:
				return 'fileUploadUnknownError';
		}
	}

	public function modifyFieldNamesForVLF($vlfName, $index) {
		parent::modifyFieldNamesForVLF($vlfName, $index);
		$this->idFieldName = CoreServices2::getRequest()->composeFormFieldName(
			array($vlfName, $index, $this->idFieldName)
		);
	}
}
?>