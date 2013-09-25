#!/usr/bin/env php
<?php
/* This file runs the travis unit tests. */

/* Define the working directory for the build */
$workingDir = getcwd();
define('__TRAVIS_DIR__', $workingDir);

$__CONFIG_ONLY__ = true;
require('travis/configuration.inc.php');

require_once(__QCUBED_CORE__ . '/tests/simpletest/unit_tester.php');
require_once(__QCUBED_CORE__ . '/tests/simpletest/reporter.php');

$__CONFIG_ONLY__ = false;
require('travis/qcubed.inc.php');

// Codegen for testing
// Running as a Non-Windows Command Name
	$strCommandName = 'codegen.cli';

	// Include the rest of the OS-agnostic script
	require('includes/_devtools_cli/codegen.inc.php');


// not using QCubed error handler for unit tests - using the SimpleTest one instead
restore_error_handler();

require_once(__QCUBED_CORE__ . '/tests/qcubed-unit/QUnitTestCaseBase.php');
require_once(__QCUBED_CORE__ . '/tests/qcubed-unit/QTestControl.class.php');


class QTravisReporter extends TextReporter {
	// Do not print passed tests. For travis we want to see only failed tests
	function paintPass($message) {
		parent::paintPass($message);
	}
}

/**
 * @var QTravisReporter 
 */
$rptReporter = null;

class QTestForm extends QForm {
	public $ctlTest;

	protected function Form_Create() {
		$this->ctlTest = new QTestControl($this);

		$filesToSkip = array(
			"QUnitTestCaseBase.php"
			, "QTestForm.tpl.php"
			, "QTestControl.class.php"
		);

		$arrFiles = QFolder::listFilesInFolder(__QCUBED_CORE__ . '/tests/qcubed-unit/');
		$arrTests = array();
		foreach ($arrFiles as $filename) {
			if (!in_array($filename, $filesToSkip)) {
				require_once(__QCUBED_CORE__ . '/tests/qcubed-unit/' . $filename);
				$arrTests[] = str_replace(".php", "", $filename);
			}
		}

		$suite = new TestSuite('QCubed ' . QCUBED_VERSION_NUMBER_ONLY . ' Unit Tests - SimpleTest ' . SimpleTest::getVersion());
		foreach ($arrTests as $className) {
			$suite->add(new $className($this));
		}
		global $rptReporter;
		$rptReporter = new QTravisReporter();
		$suite->run($rptReporter);
	}
}

QTestForm::Run('QTestForm', __DOCROOT__ . "/travis/QTestForm.tpl.php");

// Need to return value greater then zero in a case of an error.
exit (intval($rptReporter->getFailCount() + $rptReporter->getExceptionCount()));

?>
