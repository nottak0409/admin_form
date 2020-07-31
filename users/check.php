<?php
session_start();
require('../function/dbconnect.php');
require('../function/function.php');

if(!isset($_SESSION['join'])){
	header('Location: register.php');
	exit();
}

if(!empty($_POST)) {
	$statement = dbConnect()->prepare('INSERT INTO users SET name=?, email=?, password=?, created=NOW()');
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
　
	<link rel="stylesheet" href="../stylesheets/style.css" />
	<link rel="stylesheet" href="../stylesheets/bootstrap.css" />
</head>

<body>
  <div class="body">
	<h1>会員登録</h1>
　<form action="" method="post">
    <input type="hidden" name="action" value="submit" />
	  <div>
			<label for="name">名前:</label>
   		<?php echo h($_SESSION['join']['name']); ?>
		</div>
			<div>
				<label for="email">メールアドレス:</label>
			  <?php echo h($_SESSION['join']['email']); ?>
			</div>
			<div>パスワード:【表示されません】</div>
		<div><a href="index.php?action=rewrite">&laquo;&nbsp;書き直す</a> | <button type="submit" class="btn btn-primary">登録する</button></div>
  </form>
  </div>
</body>
</html>
