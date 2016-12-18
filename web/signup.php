<?php
require_once 'template/template.php';

if(!isset($_POST['mail']) ||
   !isset($_POST['username']) ||
   !isset($_POST['nickname']) ||
   !isset($_POST['password'])
	){
	// 400
	$tmpl_400 = new Template('400');
	$tmpl_400->show();
	exit();
}

$tmpl_pleasecheckemail = new Template('signup/please_check_email');
$tmpl_pleasecheckemail->show();
exit();
