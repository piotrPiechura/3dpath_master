<?php
class CoreFormFieldFile extends CoreFormAbstractField {
	protected $uploadResultStruct = null;

	/**
	 * $defaultValue is ignored
	 */
	public function __construct($name, $defaultValue = null) {
		parent::__construct($name, null);
		$this->fieldType = 'File';
	}

	/**
	 * This function is intended to be used by a validator.
	 * Error codes are PHP standard:
	 *		UPLOAD_ERR_OK;			Value: 0; There is no error, the file uploaded with success. 
	 *	The two errors below should be treated in the same way:
	 *		UPLOAD_ERR_INI_SIZE;	Value: 1; The uploaded file exceeds the upload_max_filesize directive in php.ini. 
	 *		UPLOAD_ERR_FORM_SIZE;	Value: 2; The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form. 
	 *	How to handle this?
	 *		UPLOAD_ERR_PARTIAL;		Value: 3; The uploaded file was only partially uploaded. 
	 *	This might not be an error:
	 *		UPLOAD_ERR_NO_FILE; 	Value: 4; No file was uploaded. 
	 *	The two below need to be reported to the server admin:
	 *		UPLOAD_ERR_NO_TMP_DIR; 	Value: 6; Missing a temporary folder.
	 *		UPLOAD_ERR_CANT_WRITE; 	Value: 7; Failed to write file to disk.
	 *	This should not occur - first we should check extension ourselves.
	 *		UPLOAD_ERR_EXTENSION; 	Value: 8; File upload stopped by extension.
	 */
	public function getErrorCode() {
		return $this->uploadResultStruct['error'];
	}
	
	public function setValueFromRequest($httpMethod) {
		if ($httpMethod != 'post') {
			throw new CoreException('Files can only be uploaded using \'post\' method.');
		}
		$uploadResultStruct = CoreServices::get('request')->getFileStruct($this->name);
		if (!empty($uploadResultStruct['tmp_name'])) {
			$this->uploadResultStruct = CoreServices::get('request')->getFileStruct($this->name);
		}
	}

	public function adjustSubmittedValue($submittedValue) {}

	public function getValue() {
		if ($this->uploadResultStruct['error']) {
			return null;
		}
		return $this->uploadResultStruct;
	}
}
?>