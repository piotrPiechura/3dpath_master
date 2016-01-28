<?php
abstract class CoreDBAbstractTest extends CoreTestAbstractTest {
	abstract protected function getDB();

	public function run() {
		$db = $this->getDB();
	
		// 100 characters encoded in UTF-8
		$inputValue = 
			"1' \\ \" &<>; //\n\t" . '" \\ \' &<>; // ąćęłńóśźż ĄĆĘŁŃÓŚŹŻАаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧч1';

		$sqlCreateTable = $this->getCreateTestTableSQL();
		$sqlDropTable = 'DROP TABLE test';
		$sqlInsert2Rows	= '
			INSERT INTO `test` (`test`)
			VALUES (' . $db->prepareInputValue(1) . '), (' . $db->prepareInputValue($inputValue) . ')';
		$sqlGet2Rows = 'SELECT * FROM test ORDER BY test LIMIT 0, 2';
		$sqlGetRow = 'SELECT COUNT(*) AS num FROM test';

		$this->testingEngine->tryToPass($db, 'change',  array($sqlCreateTable), 0);
		$this->testingEngine->tryToFail($db, 'change',  array($sqlCreateTable), 'CoreException');
		$this->testingEngine->tryToPass($db, 'getRows', array($sqlGet2Rows),    array());
		$this->testingEngine->tryToPass($db, 'transactionStart',  array());
		$this->testingEngine->tryToPass($db, 'change',  array($sqlInsert2Rows), 2);
		$this->testingEngine->tryToPass($db, 'transactionRollback',  array());
		$this->testingEngine->tryToPass($db, 'getRows', array($sqlGet2Rows),    array());
		$this->testingEngine->tryToPass($db, 'transactionStart',  array());
		$this->testingEngine->tryToPass($db, 'change',  array($sqlInsert2Rows), 2);
		$this->testingEngine->tryToFail($db, 'change',  array($sqlGet2Rows),    'CoreException');
		$this->testingEngine->tryToPass($db, 'getRows', array($sqlGet2Rows),    array(0 => array('test' => '1'), 1 => array('test' => $inputValue)));
		$this->testingEngine->tryToPass($db, 'transactionCommit',  array());
		$this->testingEngine->tryToPass($db, 'getRow',  array($sqlGetRow),      array('num' => '2'));
		$this->testingEngine->tryToPass($db, 'change',  array($sqlDropTable),   0);
		// below: exception is raised, but SQL is already executed - this is acceptable
		$this->testingEngine->tryToFail($db, 'getRow',  array($sqlCreateTable), 'CoreException');
		$this->testingEngine->tryToPass($db, 'change',  array($sqlDropTable),   0);
		$this->testingEngine->tryToFail($db, 'getRows', array($sqlCreateTable), 'CoreException');
		$this->testingEngine->tryToPass($db, 'change',  array($sqlDropTable),   0);
	}
}
?>