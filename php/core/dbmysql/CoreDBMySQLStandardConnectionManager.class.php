<?php
class CoreDBMySQLStandardConnectionManager {
	protected $connection = null;
	
	protected $dbHost = null;
	protected $dbPort = null;
	protected $dbUser = null;
	protected $dbPassword = null;
	protected $dbName = null;

	public function __construct($dbHost, $dbPort, $dbUser, $dbPassword, $dbName) {
		$this->dbHost = $dbHost;
		$this->dbPort = $dbPort;
		$this->dbUser = $dbUser;
		$this->dbPassword = $dbPassword;
		$this->dbName = $dbName;
	}

	public function getConnection() {
		if (!$this->connection) {
			$hostPort = $this->dbHost;
			if ($this->dbPort) {
				$hostPort .= ':' . $this->dbPort;
			}
			$this->connection = mysql_connect (
				$hostPort,
				$this->dbUser,
				$this->dbPassword,
				True
			);
			$this->checkResult(True);
			$mysqlResult = mysql_select_db($this->dbName, $this->connection);
			$this->checkResult($mysqlResult, 'select db ' . $this->dbName);
			$sql =
				'SET NAMES ' . CoreConfig::get('CoreDBMySQL', 'connectionCharset')
				. ' COLLATE ' . CoreConfig::get('CoreDBMySQL', 'connectionCollation');
			$mysqlResult = mysql_query($sql, $this->connection);
			$this->checkResult($mysqlResult, $sql);
		}
		return $this->connection;
	}

	protected function checkResult($mysqlResult, $message = '') {
		if (!$this->connection) {
			throw new CoreException('Connection refused!');
		}
		if ($mysqlResult === False) {
			$errorCode = mysql_errno($this->connection);
			$errorMessage = mysql_error($this->connection);
			throw new CoreException(
				$errorCode
				. ' : ' . $errorMessage
				. ' : ' . $message
			);
		}
	}
}
?>