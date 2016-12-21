<?php
require_once 'basic.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/template/template.php';
$setting = parse_ini_file($_SERVER['DOCUMENT_ROOT'].'/setting.ini',true);



//認証
$mail = require_basic_auth();



function is_valid_password($raw_password){
	//半角英数字をそれぞれ1種類以上含む8文字以上72文字以下の正規表現
	//(http://qiita.com/mpyw/items/886218e7b418dfed254b)
	return preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,72}+\z/i', $raw_password);
}

if(!isset($_POST['password'])){
	exit('error');
}

// postデータの取得
$raw_password = (string)filter_input(INPUT_POST, 'password');

// valid check
if(!is_valid_password($raw_password)){
	exit('書式が間違っています。');
}

// パスワードハッシュ化
$password = password_hash($raw_password, PASSWORD_DEFAULT);

//sql insert 登録
$pdo = new PDO(
	'mysql:dbname='.$setting['sql']['table'].';host='.$setting['sql']['address'].';charset=utf8mb4',
	$setting['sql']['username'],
	$setting['sql']['password'],
	[]
);

// 一番新しいNFCタグタッチログを取得してnfcタグ情報(=IDm)を取得
$stmt = $pdo->prepare('UPDATE accounts SET password=:pass WHERE mail=:mail');
$stmt->bindValue(':pass', $password);
$stmt->bindValue(':mail', $mail);
$result = $stmt->execute();
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
パスワードを変更しました。<br>
<a href="./">戻る</a>
</body>
</html>