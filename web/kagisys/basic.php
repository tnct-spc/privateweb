<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/template/template.php';

function start_basic($message = 'このページを見るにはログインが必要です。')
{
	header('WWW-Authenticate: Basic realm="Enter username and password."');
	header('Content-Type: text/plain; charset=utf-8');
	exit($message);
}

function check_account($mail, $password)
{
	$setting = parse_ini_file($_SERVER['DOCUMENT_ROOT'].'/setting.ini',true);
	$pdo = new PDO(
		'mysql:dbname='.$setting['sql']['table'].';host='.$setting['sql']['address'].';charset=utf8mb4',
		$setting['sql']['username'],
		$setting['sql']['password'],
		[]
	);

	// アカウントチェック
	$stmt = $pdo->prepare('SELECT password FROM accounts WHERE mail = :mail');
	$stmt->bindValue(':mail', $mail);
	$result = $stmt->execute();
	$result_fetched = $stmt->fetch();
	if(isset($result_fetched['password']) && password_verify($password, $result_fetched['password'])){
		return true;
	}else{
		return false;
	}
}

function require_basic_auth()
{
	session_start();
	if(!isset($_SESSION['username']) || !isset($_SESSION['password'])){
		header('Location: /kagisys/loginpage.php');
	}
	if(check_account($_SESSION['username'],$_SESSION['password'])){
		return $_SESSION['username'];
	}else{
		exit('login failure');
	}
}

/**
 * htmlspecialcharsのラッパー関数
 *
 * @param string $str
 * @return string
 */
function h($str)
{
	return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
