<?php
class HomeDAO extends CoreModelAbstractDAO {
	protected function getTableName() {
		return 'home';
	}

	protected function getFields() {
		return array(
			'id' => null,
			'homeBox1Title' => null,
			'homeBox1Subtitle' => null,
			'homeBox1Content' => null,
			'homeBox2Title' => null,
			'homeBox2Subtitle' => null,
			'homeBox2Link' => null
		);
	}

	public function getRecord() {
		return $this->getRecordById(1);
	}

	protected function hasNumerousDirectlyRelatedRecords(array &$record) {
		throw new CoreException('Method not implemented');
	}

	/**
	 * To nie powinno zostać nigdy użyte, bo jest tylko jeden rekord, nigdy nie usuwany.
	 */
	protected function getDirectlyRelatedRecords(&$record) {
		throw new CoreException('Method not implemented');
	}

	/**
	 * Bez znaczenia, bo jest tylko jeden rekord.
	 */
	protected function getDefaultOrderBySQL() {
		return 'id';
	}
	
	public function save(&$record) {
		if ($record['id'] != 1) {
			return;
		}
		parent::save($record);
	}

	public function delete(&$record) {
		throw new CoreException('Method not implemented');
	}
}
?>