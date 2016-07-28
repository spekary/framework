<?php


class BasicTestPanel extends QPanel {
    protected $suite;
    protected $objTester;
    protected $testCallback;
    protected $testCounter;

    protected $txtText;
    protected $txtText2;
    protected $lstSelect;
    protected $lstSelect2;
    protected $lstCheck;
    protected $lstCheck2;
    protected $lstRadio;
    protected $chkCheck;
    protected $rdoRadio1;
    protected $rdoRadio2;
    protected $rdoRadio3;

    /** @var  QImageButton */
    protected $btnImage;

    protected $btnServer;
    protected $btnAjax;
    protected $btnSetItemsAjax;

    public function __construct($objParent, $strControlId = null) {
        parent::__construct($objParent, $strControlId);

        $this->AutoRenderChildren = true;
        
        $this->txtText = new QTextBox($this, 'txt1');
        $this->txtText->Text = 'Default';
        $this->txtText->Name = 'TextBox';

        $this->txtText2 = new QTextBox($this, 'txt2');
        $this->txtText2->Text = 'Big';
        $this->txtText2->Name = 'TextBox';
        $this->txtText2->TextMode = QTextMode::MultiLine;
        $this->txtText2->Rows = 3;

        $this->chkCheck = new QCheckBox($this, 'chk1');
        $this->chkCheck->Name = 'CheckBox';
        $this->chkCheck->WrapLabel = true;

        $items = array (1=>'Item1', 2=>'Item2', 3=>'Item3', 4=>'Item4');
        $this->lstSelect = new QListBox($this, 'lst1');
        $this->lstSelect->AddItems ($items);
        $this->lstSelect->Name = 'Select';

        $this->lstSelect2 = new QListBox($this);
        $this->lstSelect2->AddItems ($items);
        $this->lstSelect2->Name = 'Multiselect';
        $this->lstSelect2->SelectionMode = QSelectionMode::Multiple;

        $this->lstCheck = new QCheckBoxList($this);
        $this->lstCheck->AddItems ($items);
        $this->lstCheck->Name = 'Check List';
        $this->lstCheck->RepeatDirection = QRepeatDirection::Horizontal;
        $this->lstCheck->RepeatColumns = 4;

        $this->lstCheck2 = new QCheckBoxList($this);
        $this->lstCheck2->AddItems ($items);
        $this->lstCheck2->Name = 'Check List';
        $this->lstCheck2->RepeatColumns = 1;
        $this->lstCheck2->MaxHeight = 100;
        $this->lstCheck2->WrapLabel = true;
        $this->lstCheck2->Name = 'Check List 2';

        $this->lstRadio = new QRadioButtonList($this);
        $this->lstRadio->AddItems ($items);
        $this->lstRadio->Name = 'Radio List';
        $this->lstRadio->RepeatDirection = QRepeatDirection::Horizontal;
        $this->lstRadio->RepeatColumns = 4;
        $this->lstRadio->SelectedIndex = 1;

        $this->rdoRadio1 = new QRadioButton($this);
        $this->rdoRadio1->Name = 'Item 1';
        $this->rdoRadio1->GroupName = 'MyGroup';
        $this->rdoRadio2 = new QRadioButton($this);
        $this->rdoRadio2->Name = 'Item 2';
        $this->rdoRadio2->GroupName = 'MyGroup';
        $this->rdoRadio3 = new QRadioButton($this);
        $this->rdoRadio3->Name = 'Item 3';
        $this->rdoRadio3->GroupName = 'MyGroup';

        $this->btnImage = new QImageButton($this);
        $this->btnImage->Name = 'Image Button';
        $this->btnImage->ImageUrl = __PHP_ASSETS__ . '/examples/images/data_model_thumbnail.png';
        $this->btnImage->AddAction (new QClickEvent(), new QRegisterClickPositionAction());

        $this->btnServer = new QButton ($this, 'btn1');
        $this->btnServer->Text = 'Server Submit';
        $this->btnServer->AddAction(new QClickEvent(), new QServerAction('submit_click'));

        $this->btnAjax = new QButton ($this, 'btn2');
        $this->btnAjax->Text = 'Ajax Submit';
        $this->btnAjax->AddAction(new QClickEvent(), new QAjaxAction('submit_click'));

        $this->btnSetItemsAjax = new QButton ($this);
        $this->btnSetItemsAjax->Text = 'Ajax Set Items';
        $this->btnSetItemsAjax->AddAction(new QClickEvent(), new QAjaxAction('setItems_click'));

        /* Create the test suite */
        $this->suite[] =  [
            ['txt1', 'SimText', 'ABCDEF', 'Text', 'ABCDEF'],
            ['txt2', 'SimText', 'ABCDEF', 'Text', 'ABCDEF'],
            ['chk1', 'SimClick', null, 'Checked', true],
            ['lst1', 'SimListSelect', 2 /* 2nd item */, 'SelectedValue', 2],
            ['btn1', 'SimClick', null, null, null],
        ];
        $this->suite[] =  [
            ['txt1', 'SimText', 'ABCDEFG', 'Text', 'ABCDEFG'],
            ['txt2', 'SimText', 'ABCDEFG', 'Text', 'ABCDEFG'],
            ['chk1', 'SimClick', null, 'Checked', false],
            ['lst1', 'SimListSelect', 3 /* 2nd item */, 'SelectedValue', 3],
            ['btn2', 'SimClick', null, null, null],
        ];


    }

    protected function submit_click($strFormId, $strControlId, $strParameter) {
        /*
        $this->txtText->Warning = 'Value = ' . $this->txtText->Text;
        $this->txtText2->Warning = 'Value = ' . $this->txtText2->Text;

        $this->chkCheck->Warning = 'Value = ' . $this->chkCheck->Checked;
        $this->lstSelect->Warning = 'Value = ' . $this->lstSelect->SelectedValue;
        $this->lstSelect2->Warning = 'Values = ' . implode (',', $this->lstSelect2->SelectedValues);
        $this->lstCheck->Warning = 'Values = ' . implode (',', $this->lstCheck->SelectedValues);
        $this->lstCheck2->Warning = 'Values = ' . implode (',', $this->lstCheck2->SelectedValues);
        $this->lstRadio->Warning = 'Value = ' . $this->lstRadio->SelectedValue;
        $this->rdoRadio1->Warning = 'Value = ' . $this->rdoRadio1->Checked;
        $this->rdoRadio2->Warning = 'Value = ' . $this->rdoRadio2->Checked;
        $this->rdoRadio3->Warning = 'Value = ' . $this->rdoRadio3->Checked;
        $this->btnImage->Warning = 'X = ' . $this->btnImage->ClickX . '; Y = ' . $this->btnImage->ClickY;*/
        $this->CheckTest($this->testCounter);
        $this->DoTest($this->testCounter + 1);
    }

    /**
     * Using this to optimize the setting of control properties. In particular, testing the use of javascript to
     * set particular aspects of controls so that the entire control does not need to be redrawn.
     *
     * @param $strFormId
     * @param $strControlId
     * @param $strParameter
     */
    protected function setItems_click($strFormId, $strControlId, $strParameter) {
        $this->lstSelect2->SelectedValues = [2,4];
        $this->lstCheck2->SelectedValues = [1,3];
        $this->lstRadio->SelectedIndex = 3;

        $this->chkCheck->Checked = true;
        $this->rdoRadio2->Checked = true;
    }

    public function Test($objTester, $callback) {
        // TODO: Set timer to make sure test completes. Not sure if that will work since a js error might shut timer down. Need to see if there is a hardware timer we can set.
        $this->objTester = $objTester;
        $this->testCallback = $callback;
        $this->DoTest(0);
    }

    protected function DoTest($intTestNum) {
        if (isset($this->suite[$intTestNum])) {
            $this->testCounter = $intTestNum;
            $this->SetTest($this->suite[$intTestNum]);
        }
        else {
            $callable = $this->testCallback;
            call_user_func($callable);  // indicate we are done
        }
    }

    protected function SetTest($suite) {
        foreach ($suite as $test) {
            $strControlId = $test[0];
            $fName = $test[1];
            $param = $test[2];

            $this->$fName($strControlId, $param);
        }
    }

    protected function SimText($strControlId, $strText) {
        QApplication::ExecuteControlCommand($strControlId, 'val', $strText);
        QApplication::ExecuteControlCommand($strControlId, 'changed');
    }

    protected function SimClick($strControlId, $param = null) {
        QApplication::ExecuteControlCommand($strControlId, 'click');
    }

    protected function SimListSelect($strControlId, $param) {
        QApplication::ExecuteControlCommand($strControlId, 'val', $param);
        QApplication::ExecuteControlCommand($strControlId, 'changed');
    }

    protected function CheckTest($intTestNum) {
        foreach ($this->suite[$intTestNum] as $test) {
            $strParam = $test[3];
            $testVal = $test[4];
            $strControlId = $test[0];

            if ($strParam) {
                $objControl = $this->GetChildControl($strControlId);
                $val = $objControl->$strParam;
                $this->objTester->assertEquals($testVal, $val, $strControlId . '->' . $strParam . ' == ' . print_r($val, true));
            }
        }
    }
}

