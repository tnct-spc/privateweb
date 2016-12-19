<?php

require_once 'basic.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/template/template.php';
$setting = parse_ini_file($_SERVER['DOCUMENT_ROOT'].'/setting.ini',true);

$mail = require_basic_auth();

//sql insert 登録
$pdo = new PDO(
	'mysql:dbname='.$setting['sql']['table'].';host=mysql;charset=utf8mb4',
	$setting['sql']['username'],
	$setting['sql']['password'],
	[]
);

// 一番新しいNFCタグタッチログを取得してnfcタグ情報(=IDm)を取得
$stmt = $pdo->prepare('SELECT IDm FROM touchedlog ORDER BY timestamp DESC');
$stmt->bindValue(':mail', $mail);
$result = $stmt->execute();
$IDm = $stmt->fetch()['IDm'];

// accountテーブルからアカウントのidを取得
$stmt = $pdo->prepare('SELECT id FROM accounts WHERE mail = :mail');
$stmt->bindValue(':mail', $mail);
$result = $stmt->execute();
$account_id = $stmt->fetch()['id'];

// nfctagテーブルに新しいIDmを登録
$stmt = $pdo->prepare('INSERT INTO nfctag VALUES (:IDm, :account_id)');
$stmt->bindValue(':IDm', $IDm);
$stmt->bindValue(':account_id', $account_id);
$result = $stmt->execute();
?>

<!DOCTYPE html>
<html>
        <head>
                <title></title>
        </head>
        <body>
                登録完了<br>
                <a href="index.php">
                        <button>戻る</button>
                </a>
        </body>
</html>

