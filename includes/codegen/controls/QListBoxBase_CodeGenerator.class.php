<?php

	class QListBoxBase_CodeGenerator extends QListControl_CodeGenerator {
		public function __construct($strControlClassName = 'QListBox') {
			parent::__construct($strControlClassName);
		}

		/**
		 * Reads the options from the special data file, and possibly the column
		 * @param QCodeGenBase $objCodeGen
		 * @param QTable $objTable
		 * @param QColumn|QReverseReference|QManyToManyReference $objColumn
		 * @param string $strControlVarName
		 * @return string
		 */
		public function ConnectorCreateOptions(QCodeGenBase $objCodeGen, QTable $objTable, $objColumn, $strControlVarName) {
			$strRet = parent::ConnectorCreateOptions($objCodeGen, $objTable, $objColumn, $strControlVarName);

			if ($objColumn instanceof QManyToManyReference) {
				$strRet .= <<<TMPL
			\$this->{$strControlVarName}->SelectionMode = QSelectionMode::Multiple;

TMPL;
			}
			return $strRet;
		}
	}