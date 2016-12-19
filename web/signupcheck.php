<?php
require_once 'template/template.php';

if(!isset($_GET['checkstring'])){
	// 400
	$tmpl_400 = new Template('400');
	$tmpl_400->show();
	exit();
}

$setting = parse_ini_file('setting.ini',true);

$mail = urldecode((string)filter_input(INPUT_GET, 'mail'));
$checkstring = (string)filter_input(INPUT_GET, 'checkstring');

$pdo = new PDO(
	'mysql:dbname='.$setting['sql']['table'].';host=mysql;charset=utf8mb4',
	$setting['sql']['username'],
	$setting['sql']['password'],
	[]
);

// 存在しなければエラー
$stmt = $pdo->prepare('SELECT COUNT(*) FROM accounts WHERE mail = :mail AND checkstring = :checkstring');
$stmt->bindValue(':mail', $mail);
$stmt->bindValue(':checkstring', $checkstring);
$result = $stmt->execute();
$result_fetched = $stmt->fetch();
if(!isset($result_fetched['COUNT(*)']) ||
   $result_fetched['COUNT(*)'] !== '1'){
	// 400
	$tmpl_400 = new Template('400');
	$tmpl_400->show();
	exit();
}

// 登録する
$stmt = $pdo->prepare('UPDATE accounts SET isvalid=true WHERE mail=:mail');
$stmt->bindValue(':mail', $mail);
$result = $stmt->execute();
if($result === false){
	$tmpl = new Template('500');
	$tmpl->show();
	exit();
}

$tmpl_complete = new Template('signup/complete');
$tmpl_complete->show();
exit();
