<?php
/**
 * This class helps to collect, manage and print error messages from validators.
 */
class CoreFormValidationMessageContainer {
	protected $messages = null;
	protected $badFields = null;
	protected $affectedFields = null;
	
	public function __construct() {
		$this->messages = array();
		$this->badFields = array();
		$this->affectedFields = array();
	}

	public function addMessage($message, $fieldDescriptions = null, $fieldValues = null) {
		if ($fieldDescriptions) {
			foreach ($fieldDescriptions as $fieldName => $fieldCaption) {
				$this->affectedFields[$fieldName] = 1;
			}
			$fieldNames = array_keys($fieldDescriptions);
			if (sizeof($fieldNames) == 1) {
				$this->badFields[$fieldNames[0]] = 1;
			}
			else {
				// sort to get unabmiguous index
				sort($fieldNames);
			}
			$index = implode(',', $fieldNames);
		}
		else {
			$fieldDescriptions = array();
			$index = '';
		}
		if (!array_key_exists($index, $this->messages)) {
			if (!$fieldValues) {
				$fieldValues = array();
			}
			$this->messages[$index] = array(
				'fieldNames' => array_keys($fieldDescriptions),
				'fieldCaptions' => array_values($fieldDescriptions),
				'fieldValues' => array_values($fieldValues),
				'messages' => array()
			);
		}
		$this->messages[$index]['messages'][] = $message;
	}
	
	public function isBadField($fieldName) {
		return array_key_exists($fieldName, $this->badFields);
	}

	public function isAffectedField($fieldName) {
		return array_key_exists($fieldName, $this->affectedFields);
	}

	public function getErrorCount() {
		return (sizeof($this->messages));
	}

	public function isAnyErrorMessage() {
		return ($this->getErrorCount() != 0);
	}
	
	public function getMessages() {
		return $this->messages;
	}

	public function getMessagesByFieldName($fieldName) {
		return (
			!empty($this->messages[$fieldName]['messages'])
			? $this->messages[$fieldName]['messages']
			: null
		);
	}

}
?>