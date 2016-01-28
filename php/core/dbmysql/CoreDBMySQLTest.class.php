<?php
class CoreDBMySQLTest extends CoreDBAbstractTest {
	protected function getDB() {
		return new CoreDBMySQL(
			CoreConfig::get('Environment', 'dbHost'),
			CoreConfig::get('Environment', 'dbPort'),
			CoreConfig::get('Environment', 'dbUser'),
			CoreConfig::get('Environment', 'dbPassword'),
			CoreConfig::get('Environment', 'dbName')
		);
	}

	protected function getCreateTestTableSQL() {
		return 'CREATE TABLE `test` (`test` varchar(100) CHARACTER SET utf8 COLLATE utf8_polish_ci,	PRIMARY KEY (`test`)) ENGINE = INNODB';
	}
}
?>