<?php

require_once 'basic.php';

header('Content-Type: text/html; charset=UTF-8');

// POSTが来ていなかったら登録フォームを表示
if(!isset($_POST['mail']) ||
   !isset($_POST['password'])
	){
	exit('no post');
}

// postデータの取得
$mail = (string)filter_input(INPUT_POST, 'mail');
$raw_password = (string)filter_input(INPUT_POST, 'password');

if(check_account($mail, $raw_password)){
	session_start();
	$_SESSION['username'] = $mail;
	$_SESSION['password'] = $raw_password;
	header('Location: /kagisys/index.php');
}else{
	exit('fail login.');
}


?>
