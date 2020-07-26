<?php
session_start();
require('function/dbconnect.php');
require('function/function.php');

if(!isset($_SESSION['join'])){
	header('Location: register.php');
	exit();
}

if(!empty($_POST)) {
	$statement = $db->prepare('INSERT INTO users SET name=?, email=?, password=?, created=NOW()');
	echo $statement->execute(array(
		$_SESSION['join']['name'],
		$_SESSION['join']['email'],
		sha1($_SESSION['join']['password']),
	));

  unset($_SESSION['join']);

	header('Location: thanks.php');
	exit();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>会員登録</title>
　
	<link rel="stylesheet" href="stylesheets/style.css" />
</head>

<body>
<div id="wrap">
  <div id="head">
    <h1>会員登録</h1>
  </div>
  <div id="content">
　<form action="" method="post">
    <input type="hidden" name="action" value="submit" />
	  <dl>
			<dt>名前</dt>
			<dd>
			<?php echo h($_SESSION['join']['name']); ?>
			</dd>
			<dt>メールアドレス</dt>
			<dd>
			<?php echo h($_SESSION['join']['email']); ?>
			</dd>
			<dt>パスワード</dt>
			<dd>
			【表示されません】
			</dd>
		</dl>
		<div><a href="index.php?action=rewrite">&laquo;&nbsp;書き直す</a> | <input
		type="submit" value="登録する"></div>
  </form>
  </div>

</div>
</body>
</html>
