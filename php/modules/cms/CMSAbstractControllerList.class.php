<?php
abstract class CMSAbstractControllerList extends CMSAbstractController {
	protected $recordList = null;
	protected $filter = null;
	protected $filterForm = null;
	protected $pagination = null;

	public function prepareData() {
		parent::prepareData();
		$this->initFilter();
		$this->initPagination();
		$this->initRecordList();
		$this->prepareAdditionalData();
	}

	//abstract protected function getShownColumns();
	abstract protected function getFilterTypes();

	protected function initFilterForm() {
		$this->filterForm = new CoreForm('get');
	}

	protected function initFilter() {
		$request = CoreServices::get('request');
		$sessionVarName = '_list' . md5(CoreServices::get('url')->getCurrentPageUrl());
		$this->initFilterForm();
		foreach ($this->getFilterTypes() as $fieldName => $filter) {
			$field = $filter->createField($fieldName);
			if(!empty($field)) {
				if(is_array($field)) {
					foreach($field as $_fieldName) {
						$this->filterForm->addField($_fieldName);
					}
				} else {
					$this->filterForm->addField($field);
				}
			}
		}
		$this->addOrderField();
		if ($this->filterForm->isSubmitted()) {
			$this->filterForm->setFieldValuesFromRequest();
			$this->filter = $this->getFilterTypes();
			$this->setFilterValuesFromFilterFormFields();
			$sessionVar = array(
				'filter' => $this->filter,
				'order' => $this->filterForm->getField('order')->getValue()
			);
			$request->setSession($sessionVarName, $sessionVar);
		}
		else {
			$sessionVar = $request->getFromSession($sessionVarName);
			if (!empty($sessionVar)) {
				$this->filter = $sessionVar['filter'];
				$this->setFilterFormFieldsFromUrlOrFilterValues();
				$this->filterForm->getField('order')->setValue($sessionVar['order']);
			}
			else {
				$this->filter = $this->getFilterTypes();
				$this->filterForm->getField('order')->setValue($this->getDefaultOrder());
			}
		}
	}

	protected function setFilterValuesFromFilterFormFields() {
		$betweenFields = array();
		foreach ($this->filterForm->getFields() as $fieldName => $field) {
			// szukanie pól składowych dla filtra typu between, który składa się z dwóch pól
			$fieldNameParts = explode('_', $fieldName);
			if(count($fieldNameParts) == 2 && ($fieldNameParts[1] == 'min' || $fieldNameParts[1] == 'max')) {
				$betweenFields[$fieldNameParts[0]][$fieldNameParts[1]] = $field->getValue();
			} else {
				if (array_key_exists($fieldName, $this->filter)) {
					$this->filter[$fieldName]->setValue($field->getValue());
				}
			}
		}
		// ustawianie wartości filtra dla filtrów typu between
		foreach($betweenFields as $filteredFieldName => $filter) {
			$values = array();
			foreach($filter as $key => $val) {
				$values[$key] = $val;
			}
			$this->filter[$filteredFieldName]->setValue($values);
		}
	}

	protected function setFilterFormFieldsFromUrlOrFilterValues() {
		$betweenFields = array();
		foreach ($this->filterForm->getFields() as $fieldName => $field) {
			$fieldNameParts = explode('_', $fieldName);
			if (count($fieldNameParts) == 2 && ($fieldNameParts[1] == 'min' || $fieldNameParts[1] == 'max')) {
				$betweenFields[$fieldNameParts[0]] = $this->filter[$fieldNameParts[0]]->getValue();
			} else if (array_key_exists($fieldName, $this->filter)) {
				// try to get param value from the url - handy for preinitializing filtered list with some filter values passed in url
				$requestParamValue = CoreServices2::getRequest()->getFromGet($fieldName);
				if (!empty($requestParamValue)) {
					$this->filter[$fieldName]->setValue($requestParamValue);
					$field->setValue($requestParamValue);
				} else {
					$field->setValue($this->filter[$fieldName]->getValue());
				}
			}
		}
		foreach($betweenFields as $filteredFieldName => $filter) {
			$values = array();
			foreach($filter as $key => $val) {
				$values[$key] = $val;
				$this->filterForm->getField($filteredFieldName . '_' . $key)->setValue($val);
			}
		}
	}

	protected function initPagination() {
		$this->pagination = new CorePaginationStandard($this, CoreServices::get('url')->getCurrentPageFullUrl());
		// other options:
		// $this->pagination = new CorePaginationDummy();
		// $this->pagination = new CorePaginationInitialChar($this, CoreServices::get('url')->getCurrentPageFullUrl());
	}

	/**
	 * Aquire any additional data that should be shown on that page.
	 */
	protected function prepareAdditionalData() {}

	protected function initRecordList() {
		$shownColumns = array_merge(array('id' => 'id'), $this->getFilterTypes());
		$this->recordList = $this->dao->getFilteredPaginatedList(
			$shownColumns,
			$this->filter,
			$this->pagination,
			$this->filterForm->getField('order')->getValue()
		);
	}

	public function getRecordCount() {
		return $this->dao->getFilteredCount($this->filter);
	}

	protected function getDefaultOrder() {
	 	return null;
	}

	protected function addOrderField() {
		$orderOptions = array();
		foreach (array_keys($this->getFilterTypes()) as $fieldName) {
			$orderOptions[$fieldName . ' &lt;'] = null;
			$orderOptions[$fieldName . ' &gt;'] = null;
		}		
		$this->filterForm->addField(new CoreFormFieldSelect(
			'order',
			null,
			$orderOptions
		));
	}

	/**
	 * Likely to be overwritten if initAdditions() is not empty.
	 */
	public function assignDisplayVariables() {
		parent::assignDisplayVariables();
		//CoreServices::get('display')->assign('shownColumns', $this->getShownColumns());
		CoreServices::get('display')->assign('shownColumns', $this->getFilterTypes());
		CoreServices::get('display')->assign('recordList', $this->recordList);
		CoreServices::get('display')->assign('pagination', $this->pagination);
		CoreServices::get('display')->assign('filterForm', $this->filterForm);
	}
}
?>