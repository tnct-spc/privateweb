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
	</body>
</html>