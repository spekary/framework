<?php
require_once('../qcubed.inc.php');

$files = QFolder::ListFilesInFolder(__DIR__, true);

$strHtml = "<ul>\n";
foreach ($files as $strFile) {
	if (strpos($strFile, 'test') === 0 ||
		strpos($strFile, 'tpl.php')) {
		// don't add
	} else {
		$strHtml .= "<li><a href='$strFile'>$strFile</a></li>\n";
	}
}
$strHtml .= "</ul>\n";

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Tests</title>
</head>
<body>
<h1>Various UI and other tests of the framework.</h1>
<?= $strHtml ?>
</body>
</html>
