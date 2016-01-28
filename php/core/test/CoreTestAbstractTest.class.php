<?php
abstract class CoreTestAbstractTest {
	protected $testingEngine = null;

	public function __construct($testingEngine) {
		$this->testingEngine = $testingEngine;
	}

	abstract public function run();
}
?>
