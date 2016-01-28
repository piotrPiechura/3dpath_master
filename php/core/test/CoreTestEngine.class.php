<?php
class CoreTestEngine {
	protected $errorReports = null;

	public function __construct() {}

	public function run($testNames) {
		$this->errorReports = "";
		foreach ($testNames as $testName) {
			echo($testName);
			$test = new $testName($this);
			$timeStart = microtime(True);
			$test->run();
			$timeEnd = microtime(True);
			$executionTime = number_format(($timeEnd - $timeStart), 3);
			echo(' : ' . $executionTime . ' s<br/>');
		}
		if (!$this->errorReports) {
			$this->errorReports = "<br/>OK!";
		}
		echo($this->errorReports);
	}

	public function tryToPass($object, $method, $args, $expectedResult = null, $expectedOutput = null) {
		$phpCode = $this->buildPHPCode($object, $method, $args);
		$phpForMessage = $this->buildPHPForMessage($object, $method, $args);
		try {
			ob_start();
			eval($phpCode);
			$output = ob_get_contents();
			ob_end_clean();
			if ($result != $expectedResult) {
				$this->addErrorReport($phpForMessage, $expectedResult, $result, null, null, null, null);
			}
			if ($output && $output != $expectedOutput) {
				$this->addErrorReport($phpForMessage, null, null, $expectedOutput, $output, null, null);
			}
		}
		catch (CoreException $e) {
			$this->addErrorReport($phpForMessage, null, null, null, null, null, get_class($e) . ' : ' . $e->getMessage());
		}
	}

	public function tryToFail($object, $method, $args, $expectedExceptionClass) {
		$phpCode = $this->buildPHPCode($object, $method, $args);
		$phpForMessage = $this->buildPHPForMessage($object, $method, $args);
		try {
			eval($phpCode);
			if (is_null($result)) {
				$result = 'null';
			}
			$this->addErrorReport($phpForMessage, null, null, null, null, $expectedExceptionClass, 'No exception! Returned value: ' . $result);
		}
		catch (CoreException $e) {
			$actualExcepionClass = get_class($e);
			if ($actualExcepionClass != $expectedExceptionClass) {
				$this->addErrorReport($phpForMessage, null, null, null, null, $expectedExceptionClass, $actualExcepionClass . ' : ' . $e->getMessage());
			}
		}
	}

	protected function buildPHPCode($object, $method, $args) {
		$phpCode = '$result = $object->$method(';
		for ($i = 0; $i < sizeof($args); $i ++) {
			if ($i > 0) {
				$phpCode .= ', ';
			}
			$phpCode .= '$args[' . $i . ']';
		}
		$phpCode .= ');';
		return $phpCode;
	}

	protected function buildPHPForMessage($object, $method, $args) {
		$phpCode = '$result = ' . get_class($object) . '->' . $method . '(';
		for ($i = 0; $i < sizeof($args); $i ++) {
			if ($i > 0) {
				$phpCode .= ', ';
			}
			$phpCode .= serialize($args[$i]);
		}
		$phpCode .= ');';
		return $phpCode;
	}
	
	protected function addErrorReport($phpCode, $expectedResult, $result, $expectedOutput, $output, $expectedException, $exception) {
		$report = '<br/>FAILED PHP STATEMENT:<br/>' . $phpCode;
		if (is_null($expectedResult)) {
			$expectedResult = 'null';
		}
		$report .= '<br/>EXPECTED RESULT: ' . serialize($expectedResult);
		
		if (is_null($result))  {
			$result = 'null';
		}
		$report .= '<br/>ACTUAL RESULT: ' . serialize($result);

		if (is_null($expectedOutput)) {
			$expectedOutput = 'null';
		}
		$report .= '<br/>EXPECTED OUTPUT: ' . serialize($expectedOutput);
		
		if (is_null($output))  {
			$output = 'null';
		}
		$report .= '<br/>ACTUAL OUTPUT: ' . serialize($output);

		if (is_null($expectedException)) {
			$expectedException = 'null';
		}
		$report .= '<br/>EXPECTED EXCEPTION: ' . serialize($expectedException);
		
		if (is_null($exception))  {
			$exception = 'null';
		}
		$report .= '<br/>ACTUAL EXCEPTION: ' . serialize($exception);

		$this->errorReports .= '<br/>' . $report;
	}
}
?>