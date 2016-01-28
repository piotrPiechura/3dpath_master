<?php
class CoreForm {
	protected $fields = null;
	protected $httpMethod = null;
	protected $actionHTML = null;
	protected $validators = null;
	protected $tokenManager = null;
	protected $submitted = null;
	protected $tokenFieldName = null;

	/**
	 * $formId is necessary if there's more than one form handled by one script.
	 */
	public function __construct($httpMethod = 'post', $actionHTML = null, $formId = null) {
		$this->tokenFieldName = ($formId ? '_token_' . $formId : '_token');
		$this->httpMethod = $httpMethod;
		$this->fields = array();
		$this->actionHTML = ($actionHTML ? $actionHTML : CoreServices::get('url')->getCurrentPageUrlHTML());
		$this->addFieldNoValidators(new CoreFormFieldHidden($this->tokenFieldName));
		switch ($this->httpMethod) {
			case 'post':
				$this->initTokenManagerForPostRequest();
				$this->fields[$this->tokenFieldName]->setValue($this->tokenManager->createToken());
				break;
			case 'get':
				$this->initTokenManagerForGetRequest();
				$this->fields[$this->tokenFieldName]->setValue($this->tokenManager->createToken());
				$params = CoreServices::get('url')->createGetParamsTable($this->actionHTML);
				$this->actionHTML = CoreServices::get('url')->getFullPath();
				foreach ($params as $name => $value) {
					$this->addFieldNoValidators(new CoreFormFieldHidden($name));
					$this->fields[$name]->setValue($value);
				}
				break;
			default:
				throw new CoreException('Invalid HTTP method: ' . $this->httpMethod . '!');
		}
		$this->validators = array();
	}

	protected function initTokenManagerForPostRequest() {
		$this->tokenManager = new CoreFormTokenManagerDB();
	} 

	protected function initTokenManagerForGetRequest() {
		$this->tokenManager = new CoreFormTokenManagerDummy();
	} 
	
	public function getMethod() {
		return $this->httpMethod;
	}
	
	public function getActionHTML() {
		return $this->actionHTML;
	}

	protected function addFieldNoValidators($field) {
		$fieldName = $field->getName();
		if (array_key_exists($fieldName, $this->fields)) {
			throw new CoreException('Tried to add field \'' . $fieldName . '\' twice.');
		}
		$this->fields[$fieldName] = $field;
	}

	/**
	 * This function informs the form object about the field
	 * and in case the form was submitted, it also adds some default validators.
	 */
	public function addField($field) {
		$this->addFieldNoValidators($field);
		if ($this->isSubmitted()) {
			switch ($field->getType()) {
				case 'Select':
					$this->addValidator(new CoreFormValidatorStandardSelectCheck($field->getName()));
					break;
				case 'Multiselect':
					$this->addValidator(new CoreFormValidatorStandardMultiselectCheck($field->getName()));
					break;
				case 'BBCode':
				case 'HTML':
					$this->addValidator(new CoreFormValidatorPartialHTMLCheck($field->getName()));
					break;
			}
		}
	}

	public function hasField($fieldName) {
		return array_key_exists($fieldName, $this->fields);
	}
	
	public function getField($fieldName) {
		return $this->fields[$fieldName];
	}

	public function getFields() {
		return $this->fields;
	}

	public function isSubmitted() {
		if (is_null($this->submitted)) {
			if (!CoreServices2::getRequest()->isNotEmptyRequest($this->httpMethod)) {
				$this->submitted = False;
			}
			else {
				$this->fields[$this->tokenFieldName]->setValueFromRequest($this->httpMethod);
				$tokenSubmitted = $this->fields[$this->tokenFieldName]->getValue();
				if ($this->httpMethod == 'post') {
					// Reset token, so it's possible to send this form again
					$this->fields[$this->tokenFieldName]->setValue($this->tokenManager->createToken());
					$this->submitted = $this->tokenManager->isValidToken($tokenSubmitted);
				}
				else {
					// Ignore token value; check only if form was sent.
					$this->submitted = !is_null($tokenSubmitted);
				}
			}
		}
		return $this->submitted;
	}

	public function setFieldValuesFromRequest() {
		foreach ($this->fields as $fieldName => $field) {
			if ($fieldName != $this->tokenFieldName) {
				$field->setValueFromRequest($this->httpMethod);
			}
		}
	}

	public function setFieldValuesFromRecord($record) {
		foreach ($this->fields as $fieldName => $field) {
			if (array_key_exists($fieldName, $record)) {
				$field->setValue($record[$fieldName]);
			}
			elseif (
				$field->getType() == 'Multiselect'
				&& array_key_exists('_manyToMany', $record)
				&& array_key_exists($fieldName, $record['_manyToMany'])
			) {
				$field->setValue($record['_manyToMany'][$fieldName]);
			}
		}
	}

	public function setRecordValuesFromFields(&$record) {
		foreach ($this->fields as $fieldName => $field) {
			if (array_key_exists($fieldName, $record)) {
				$record[$fieldName] = $field->getValue();
			}
			elseif (
				$field->getType() == 'Multiselect'
				&& array_key_exists('_manyToMany', $record)
				&& array_key_exists($fieldName, $record['_manyToMany'])
			) {
				$record['_manyToMany'][$fieldName] = $field->getValue();
			}
		}
	}

	public function addValidator($validator) {
		$validator->setForm($this);
		$this->validators[] = $validator;
	}

	/**
	 * @return CoreFormValidationMessageContainer
	 */
	public function getValidationResults() {
		$messageManager = new CoreFormValidationMessageContainer();
		foreach ($this->validators as $validator) {
			$validator->validate($messageManager);
		}
		return $messageManager;
	}
}
?>