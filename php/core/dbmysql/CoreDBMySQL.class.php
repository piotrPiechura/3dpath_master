<?php
class CoreDBMySQL implements iCoreDB {
	// No i już to nie jest IMMUTABLE OBJECT...
	protected $transactionStarted;
	protected $connectionManager;

	public function __construct($dbHost, $dbPort, $dbUser, $dbPassword, $dbName) {
		$this->transactionStarted = False;
		$this->connectionManager = new CoreDBMySQLStandardConnectionManager($dbHost, $dbPort, $dbUser, $dbPassword, $dbName);
	}

	public function transactionStart() {
		if ($this->transactionStarted) {
			throw new CoreException('Tried to start transaction within another transaction');
		}
		$this->transactionStarted = True;
		$this->change('SET TRANSACTION ISOLATION LEVEL SERIALIZABLE');
		$this->change('START TRANSACTION');
	}
	
	public function transactionCommit() {
		//if (!$this->transactionStarted) {
		//	throw new CoreException('Tried to commit transaction that was not started');
		//}
		$this->change('COMMIT');
		$this->transactionStarted = False;
	}
	
	public function transactionRollback() {
		//if (!$this->transactionStarted) {
		//	throw new CoreException('Tried to rollback transaction that was not started');
		//}
		$this->change('ROLLBACK');
		$this->transactionStarted = False;
	}

	public function change($sql) {
		$connection = $this->connectionManager->getConnection();
		$mysqlResult = mysql_query($sql, $connection);
		$this->checkResult($connection, $mysqlResult, $sql, True);
		return mysql_affected_rows($connection);
	}

	public function insertRow($sql) {
		$connection = $this->connectionManager->getConnection();
		$mysqlResult = mysql_query($sql, $connection);
		$this->checkResult($connection, $mysqlResult, $sql, True);
		$insertResult = mysql_affected_rows($connection);
		if ($insertResult != 1) {
			throw new CoreException('Unexpected result: ' . $insertResult . ' rows changed instead of single row; the SQL was: ' . $sql);
		}
		return mysql_insert_id($connection);
	}


	public function getRow($selectSQL) {
		$connection = $this->connectionManager->getConnection();
		$mysqlResult = mysql_query($selectSQL, $connection);
		$this->checkResult($connection, $mysqlResult, $selectSQL, False);
		$row = mysql_fetch_assoc($mysqlResult);
		if (!$row) {
			throw new CoreException('No data acquired by getRow() function! : ' . $selectSQL);
		}
		if (mysql_fetch_assoc($mysqlResult)) {
			throw new CoreException('More than one record acquired by getRow() function! : ' . $selectSQL);
		}
		return $row;
	}

	public function getRows($selectSQL) {
		$connection = $this->connectionManager->getConnection();
		$mysqlResult = mysql_query($selectSQL, $connection);
		$this->checkResult($connection, $mysqlResult, $selectSQL, False);
		$retVal = array();
		while ($row = mysql_fetch_assoc($mysqlResult)) {
			$retVal[] = $row;
		}
		return $retVal;
	}
	
	public function prepareInputValue($rawValue) {
		if (is_null($rawValue)) {
			return 'NULL';
		}
		elseif (is_int($rawValue)) { // Warunek "if (ctype_digit($rawValue))" powoduje nieprzyjemne b��dy!!!
			return intval($rawValue);
		}
		else {
			return '\'' . mysql_real_escape_string($rawValue, $this->connectionManager->getConnection()) . '\'';
		}
	}

	public function getInputSize($rawValue) {
		$value = $this->prepareInputValue($rawValue);
		if ($value == 'NULL') {
			return 0;
		}
		return strlen($value) - 2;
	}

	protected function checkResult($connection, $mysqlResult, $sql, $expectBooleanResult) {
		if ($mysqlResult === False) {
			$errorCode = mysql_errno($connection);
			$errorMessage = mysql_error($connection);
			throw new CoreException($errorCode . ' : ' . $errorMessage . ' : ' . $sql);
		}
		if ($expectBooleanResult && !is_bool($mysqlResult)) {
			throw new CoreException('Expected SQL statement changing database state: ' . $sql);
		}
		if (!$expectBooleanResult && is_bool($mysqlResult)) {
			throw new CoreException('Expected SQL SELECT statement: ' . $sql);
		}
	}
}
?>