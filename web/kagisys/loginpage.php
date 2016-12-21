<!DOCTYPE html>
<html>
	<head>
		<script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.1.1.min.js"></script>
		<script src="/js/jsSHA/src/sha.js"></script>
		<title>室内Web</title>
	</head>
	<body>
		<font color="red">
		<?php echo isset($errormessage)? $errormessage.'<br>' : '' ?>
		</font>
		ログイン<br>
		<form id="signup" action="login.php" method="post">
			メールアドレス<input type="mail" name="mail" value="<?php echo $last_mail ?>" placeholder="サイボウズに登録されているアドレス"><br>
			パスワード<input id="password" type="password" name="password" placeholder="英数字8〜16文字"><br>
			<button type="submit">登録</button>
		</form>
		<script type="text/javascript" charset="utf-8">
			$('#signup').submit(function(){
				// パスワードをsha256でハッシュにする
				var password = $('#password').val();
				var sha = new jsSHA('SHA-256', 'TEXT');
				sha.update(password);
				var sha256_password = sha.getHash('HEX');
				$('#password').val(sha256_password);
			});
		</script>
	</body>
</html>
