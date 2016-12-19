<?php
require_once 'template/template.php';

function random_string($length = 8)
{
    return substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyz', $length)),0,$length);
}

function is_valid_mail($mail){
	return (bool)filter_var($mail, FILTER_VALIDATE_EMAIL);
}

function is_valid_username($username){
	//[英数_]3から16文字
	return preg_match('/\A\w{3,16}+\z/', $username);
}

function is_valid_nickname($nickname){
	//[英数_日本語]1から16文字
	return preg_match('/\A\w{1,16}+\z/u', $nickname);
}

function is_valid_password($raw_password){
	//半角英数字をそれぞれ1種類以上含む8文字以上72文字以下の正規表現
	//(http://qiita.com/mpyw/items/886218e7b418dfed254b)
	return preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,72}+\z/i', $raw_password);
}

$setting = parse_ini_file('setting.ini',true);

// 400 check
if(!isset($_POST['mail']) ||
   !isset($_POST['username']) ||
   !isset($_POST['nickname']) ||
   !isset($_POST['password'])
	){
	$tmpl = new Template('400');
	$tmpl->show();
	exit();
}

// postデータの取得
$mail = (string)filter_input(INPUT_POST, 'mail');
$username = (string)filter_input(INPUT_POST, 'username');
$nickname = (string)filter_input(INPUT_POST, 'nickname');
$raw_password = (string)filter_input(INPUT_POST, 'password');

// valid check
if(!is_valid_mail($mail) ||
   !is_valid_username($username) ||
   !is_valid_nickname($nickname) ||
   !is_valid_password($raw_password)
	){
	$tmpl_signup = new Template('signup/form');
	$tmpl_signup->errormessage = '書式が間違っています。';
	$tmpl_signup->show();
	exit();
}

// パスワードハッシュ化
$password = password_hash($raw_password, PASSWORD_DEFAULT);


$pdo = new PDO(
	'mysql:dbname='.$setting['sql']['table'].';host='.$setting['sql']['address'].';charset=utf8mb4',
	$setting['sql']['username'],
	$setting['sql']['password'],
	[]
);

// チェック文字列生成
$checkstring = random_string(32);

// ホワイトリストに存在しなければエラー
$stmt = $pdo->prepare('SELECT COUNT(*) FROM whitelist WHERE mail = :mail');
$stmt->bindValue(':mail', $mail);
$result = $stmt->execute();
$result_fetched = $stmt->fetch();
if(!isset($result_fetched['COUNT(*)']) ||
   $result_fetched['COUNT(*)'] !== '1'){
	$tmpl_signup = new Template('signup/form');
	$tmpl_signup->errormessage = 'このメールアドレスはホワイトリストに登録されていません。';
	$tmpl_signup->show();
	exit();
}

// すでに登録済みならエラー
$stmt = $pdo->prepare('SELECT isvalid FROM accounts WHERE mail = :mail');
$stmt->bindValue(':mail', $mail);
$result = $stmt->execute();
$result_fetched = $stmt->fetch();
if($result === false){
	$tmpl = new Template('500');
	$tmpl->show();
	exit();
}
if(isset($result_fetched['isvalid']) && $result_fetched['isvalid'] == 1){
	$tmpl_signup = new Template('signup/form');
	$tmpl_signup->errormessage = 'このメールアドレスは既に登録されています。';
	$tmpl_signup->show();
	exit();
}
$is_account_find = isset($result_fetched['isvalid']) ? true : false;

// 仮登録
if($is_account_find){
	$stmt = $pdo->prepare('UPDATE accounts SET username=:username,nickname=:nickname,password=:password,checkstring=:checkstring WHERE mail=:mail');
}else{
	$stmt = $pdo->prepare('INSERT INTO accounts (mail,username,nickname,password,checkstring) VALUES(:mail,:username,:nickname,:password,:checkstring)');
}
$stmt->bindValue(':mail', $mail);
$stmt->bindValue(':username', $username);
$stmt->bindValue(':nickname', $nickname);
$stmt->bindValue(':password', $password);
$stmt->bindValue(':checkstring', $checkstring);
$result = $stmt->execute();
if($result === false){
	$tmpl = new Template('500');
	$tmpl->show();
	exit();
}

// メール送信(dockerの現設定では送れない。)
mb_language("Japanese");
mb_internal_encoding("UTF-8");
$to      = $mail;
$subject = '部室Webユーザー本登録メール';
$message = $setting['system']['address'].'/signupcheck.php?mail='.urlencode($mail).'&checkstring='.$checkstring.'\nにアクセスして登録を完了してください。';
$headers = 'From: tnct@hoge.jp' . "\r\n";
mb_send_mail($to, $subject, $message, $headers);
echo $message;

$tmpl_pleasecheckemail = new Template('signup/please_check_email');
$tmpl_pleasecheckemail->mail = $mail;
$tmpl_pleasecheckemail->show();
exit();
