<?php

// Must list all panel test files here so that test can load them while in the middle of the test
require_once(__QCUBED_CORE__ . '/tests/panels/BasicTestPanel.php');

/**
 * 
 * @package Tests
 */
class QUiTests extends QUnitTestCaseBase {
	protected $objForm;
	protected $files;
	protected function setUp()
	{
		global $_FORM;
		$this->objForm = $_FORM;
	}

	public function testBasic()
	{
		$this->objForm->pnlUiTests->RemoveChildControls(true);
		$pnl = new BasicTestPanel($this->objForm->pnlUiTests);
		$pnl->Test($this, [$this, 'testDone']);
	}

	public function testDone() {
		// todo: next test
		$a = 1;
	}
}
