<?php
session_start();
require('function/dbconnect.php');
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>トップページ</title>

  <link rel="stylesheet" href="stylesheets/style.css" />
</head>

<body>
<a href="users/login.php">ログインの方はこちら</a>
<a href="users/register.php">会員登録がまだの方はこちら</a>
</body>
</html>
