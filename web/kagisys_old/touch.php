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
		タッチしてください<br>
		<a href="submit.php">
			<button>Next</button>
		</a>
	</body>
</html>
