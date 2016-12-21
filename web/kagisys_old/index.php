<?php

require_once 'basic.php';
require_basic_auth();

header('Content-Type: text/html; charset=UTF-8');

?>
<!DOCTYPE html>
<html>
	<head>
		<title></title>
	</head>
	<body>
		ドアの前に来て<br>
		<a href="touch.php">
			<button>Next</button>
		</a>

		<br><br><br>パスワード変更フォーム<br>
		<font color="red"><p id="error"></p></font>
		<form id="signup" action="../kagisys/change_pass.php" method="post">
			パスワード<input id="password" type="password" name="password" placeholder="英数字8〜16文字"><br>
			パスワード(確認)<input id="password2" type="password" name="password2" placeholder="英数字8〜16文字"><br>
			<button type="submit">登録</button>
		</form>
		<script type="text/javascript" charset="utf-8">
			$('#signup').submit(function(){
				// パスワードをsha256でハッシュにする
				var password = $('#password').val();
				var password2 = $('#password2').val();
				if(password != password2){
					$('#error').text('パスワードが一致しません');
					return false;
				}
				var sha = new jsSHA('SHA-256', 'TEXT');
				sha.update(password);
				var sha256_password = sha.getHash('HEX');
				$('#password').val(sha256_password);
			});
		</script>
	</body>
</html>
