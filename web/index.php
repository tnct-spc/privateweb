<!DOCTYPE html>
<html>
	<head>
		<title>室内Web</title>
	</head>
	<body>
		ユーザー登録フォーム<br>
		<form action="signup.php" method="post">
			メールアドレス<input type="mail" name="mail" placeholder="サイボウズに登録されているアドレス"><br>
			ユーザー名<input type="text" name="username" placeholder="英数字3〜8文字"><br>
			ニックネーム<input type="text" name="nickname" placeholder="1〜16文字"><br>
			パスワード(<strong>サーバーに平文で送信されます</strong>)<input type="password" name="password" placeholder="英数字8〜16文字"><br>
			<button>登録</button>
		</form>
	</body>
</html>
