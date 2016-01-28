<?php
/**
 * Interface that must be satisfied by any data base abstraction implementation
 * used in this framework.
 *
 * Each method of this class should throw CoreException in case it is used
 * with violation of usage rules described in comments. This is acceptable, that
 * SQL is executed BEFORE CoreException exception is thrown, and there
 * is some mess left in data base, because we assume this can only happen during
 * system development.
 *
 * Each method can throw CoreException in case of problems with connection, or
 * DB structure errors. If this kind of error occurs, there should bo no garbage
 * left in DB.
 */
interface iCoreDB {
	public function transactionStart();
	public function transactionCommit();
	public function transactionRollback();

	/**
	 * The only mathod that can be used to change data base structure or content.
	 * This can not be used for select statements.
	 * Returns number of affected rows.
	 */
	public function change($sql);

	/**
	 * This method inserts a single record and returns its automatically created id.
	 */
	public function insertRow($sql);

	/**
	 * Returns exactly one row as an associative array.
	 * Values from DB are returned without any DBMS-specific escape characters.
	 * Empty query result causes exception.
	 * This can only be used for select statements.
	 */
	public function getRow($sql);

	/**
	 * Returns array of rows, each row as an associative array.
	 * Values from DB are returned without any DBMS-specific escape characters.
	 * In case of empty query result, an empty array is returned.
	 * This can only be used for select statements.
	 */
	public function getRows($sql);

	/**
	 * Adds quotes and escape characters compliant with the respective DBMS API.
	 */
	public function prepareInputValue($rawValue);

	/**
	 * Returns real length of database representation of data.
	 */
	public function getInputSize($rawValue);
}
?>