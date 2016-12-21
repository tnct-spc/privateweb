<!DOCTYPE html>
<html>
	<head>
		<title>室内Web</title>
	</head>
	<body>
		<font color="red">
		<?php echo isset($errormessage)? $errormessage.'<br>' : '' ?>
		</font>
		ユーザー登録フォーム<br>
		<form action="<?php echo $redirect_url ?>" method="post">
			メールアドレス<input type="mail" name="mail" value="<?php echo $last_mail ?>" placeholder="サイボウズに登録されているアドレス"><br>
			ユーザー名<input type="text" name="username" value="<?php echo $last_username ?>" placeholder="英数字3〜16文字"><br>
			ニックネーム(4バイト文字未対応)<input type="text" name="nickname" value="<?php echo $last_nickname ?>" placeholder="1〜16文字"><br>
			パスワード(<strong>サーバーに平文で送信されます</strong>)<input type="password" name="password" placeholder="英数字8〜16文字"><br>
			<button>登録</button>
		</form>
	</body>
</html>
