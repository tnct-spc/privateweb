<?php
require_once 'template/template.php';

if(!isset($_GET['check_string'])){
	// 400
	$tmpl_400 = new Template('400');
	$tmpl_400->show();
	exit();
}

$tmpl_complete = new Template('signup/complete');
$tmpl_complete->show();
exit();
