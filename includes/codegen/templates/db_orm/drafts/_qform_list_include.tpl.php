<?php
	/** @var QTable $objTable */
	/** @var QDatabaseCodeGen $objCodeGen */
	global $_TEMPLATE_SETTINGS;
	$_TEMPLATE_SETTINGS = array(
		'OverwriteFlag' => true,
		'DocrootFlag' => true,
		'DirectorySuffix' => '',
		'TargetDirectory' => __FORM_DRAFTS__,
		'TargetFileName' => QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName) . '_list.tpl.php'
	);
?>
<?php print("<?php\n"); ?>
	// This is the HTML template include file (.tpl.php) for the <?= QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName) ?>_list.php
	// form DRAFT page.  Remember that this is a DRAFT.  It is MEANT to be altered/modified.

	// Be sure to move this out of this directory before modifying to ensure that subsequent
	// code re-generations do not overwrite your changes.

	$strPageTitle = QApplication::Translate('<?= $objTable->ClassNamePlural ?>') . ' - ' . QApplication::Translate('List All');
	require(__CONFIGURATION__ . '/header.inc.php');
?>

	<?php print("<?php"); ?> $this->RenderBegin() ?>

	<h1><?php print("<?php"); ?> _t('Listing All'); ?> <?php print("<?php"); ?> _t('<?= $objTable->ClassNamePlural ?>'); ?></h1>
	<h2><a href="<?php print("<?php"); ?> _p(__VIRTUAL_DIRECTORY__ . __FORM_DRAFTS__) ?>/index.php">&laquo; <?php print("<?php"); ?> _t('Go to "Form Drafts"'); ?></a></h2>

	<?php print("<?php"); ?> $this->dtg<?= $objTable->ClassNamePlural ?>->Render(); ?>

	<p class="create">
		<a href="<?php print("<?php"); ?> _p(__VIRTUAL_DIRECTORY__ . __FORM_DRAFTS__) ?>/<?= QConvertNotation::UnderscoreFromCamelCase($objTable->ClassName) ?>_edit.php"><?php print("<?php"); ?> _t('Create a New'); ?> <?php print("<?php"); ?> _t('<?= $objTable->ClassName ?>');?></a>
	</p>

	<?php print("<?php"); ?> $this->RenderEnd() ?>

<?php print("<?php"); ?> require(__CONFIGURATION__ . '/footer.inc.php'); ?>