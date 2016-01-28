<?php
class CompanyDAO extends CoreModelAbstractDAO {
	protected function getTableName() {
		return 'company';
	}

	protected function getFields() {
		return array(
			'id' => null,
			'userId' => null,
			'companyName' => null,
                        'companyDivision' => null,
                        'companyAddress' => null,
                        'companyPhone' => null,
                        'comapanyEmail' => null,
                        'companySurveyCalcMethod' => null
		);
	}

	protected function getManyToManyRelations()	{
		return array();
	}

	protected function hasNumerousDirectlyRelatedRecords(array &$record) {

	}

	protected function getDirectlyRelatedRecords(&$record) {
		return array();
	}

	protected function getDefaultOrderBySQL() {
		return 'companyName ASC, id';
	}

	public function getListForAutoSuggest($query, $limit) {
		$db = CoreServices::get('db');
		$queryConditionSQL = '0';
		$parts = explode(' ', $query);
		$names = array();
		for ($i = 0; $i < min(2, sizeof($parts)); $i++) {
			$names[] = $db->prepareInputValue('%' . $parts[$i] . '%');
		}
		foreach (array('companyName') as $colName) {
			foreach ($names as $nameSQL) {
				$queryConditionSQL .= '
					OR ' . $colName . ' LIKE ' . $nameSQL;
			}
		}
		$sql = '
			SELECT *
			FROM company
			WHERE
				(' . $queryConditionSQL . ')
			ORDER BY
				authorName';
		if ($limit) {
			$sql .= '
			LIMIT 0, ' . $db->prepareInputValue($limit);
		}
		return $db->getRows($sql);
	}
        
        public function getCompanyByUserId($userId){
		$db = CoreServices2::getDB();
		$sql = '
			SELECT
				id, companyName
			FROM
				company
			WHERE
				userId = ' . $db->prepareInputValue($userId);
				
		return $db->getRows($sql);
        }
                
        public function getRecordPath($recordId){
                $db = CoreServices2::getDB();
		$sql = '
			SELECT
				company.id as companyId, companyName
			FROM
                                company
			WHERE
				company.id = ' . $db->prepareInputValue($recordId) .'
                        LIMIT 0,1';
                
		return $db->getRow($sql);
        }
        

}
?>