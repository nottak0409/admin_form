<?php
session_start();

require('../function/dbconnect.php');
require('../function/function.php');

if(isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
	$_SESSION['time'] = time();

	$users = $db->prepare('SELECT * FROM users WHERE id=?');
	$users->execute(array($_SESSION['id']));
	$user = $users->fetch();
} else {
	header('Location: ../users/login.php'); exit();
}


if($_SESSION['ticket'] != '') {
    $methodOfPayment = implode(',', $_SESSION['ticket']['method_of_payment']);
  if(!empty($_POST)) {
		$message = $db->prepare('INSERT INTO posts SET user_id=?, name=?, title=?, picture=?, small_title=?, content=?, business_hours=?, business_hours_text=?, address=?, tel=?, average_budget=?, method_of_payment=?, qualified=?, genre=?, other_genre=?, created=NOW()');
		$message->execute(array(
			$user['id'],
			$_SESSION['ticket']['name'],
			$_SESSION['ticket']['title'],
      $_SESSION['ticket']['picture'],
      $_SESSION['ticket']['small_title'],
      $_SESSION['ticket']['content'],
      $_SESSION['ticket']['business_hours'],
      $_SESSION['ticket']['business_hours_text'],
      $_SESSION['ticket']['address'],
      $_SESSION['ticket']['tel'],
      $_SESSION['ticket']['average_budget'],
      $methodOfPayment,
      $_SESSION['ticket']['qualified'],
			$_SESSION['ticket']['genre'],
      $_SESSION['ticket']['other_genre'])
    );
    unset($_SESSION['ticket']);
    header('Location: list.php');
  }
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
</head>

<body>
<div id="wrap">
  <div id="head">
    <h1>投稿確認画面</h1>
  </div>
  <div id="content">
　<form action="" method="post">
    <input type="hidden" name="action" value="submit" />
	  <dl>
			<dt>会社名、イベント名</dt>
			<dd>
			<?php echo h($_SESSION['ticket']['name']); ?>
			</dd>
			<dt>タイトル</dt>
			<dd>
			<?php echo h($_SESSION['ticket']['title']); ?>
			</dd>
      <dt>写真</dt>
			<dd>
        <img src="../picture/<?php echo h($_SESSION['ticket']['picture']); ?>" width="100" height="100" alt="" />
			</dd>
      <dt>小タイトル</dt>
      <dd>
      <?php echo h($_SESSION['ticket']['small_title']); ?>
      </dd>
      <dt>内容</dt>
			<dd>
			<?php echo h($_SESSION['ticket']['content']); ?>
			</dd>
      <dt>営業時間</dt>
			<dd>
			<?php echo h($_SESSION['ticket']['business_hours']); ?>
			</dd>
      <dt>営業内容</dt>
      <dd>
      <?php echo h($_SESSION['ticket']['business_hours_text']); ?>
      </dd>
      <dt>住所</dt>
			<dd>
			<?php echo h($_SESSION['ticket']['address']); ?>
			</dd>
      <dt>電話番号</dt>
			<dd>
			<?php echo h($_SESSION['ticket']['tel']); ?>
			</dd>
      <dt>平均予算</dt>
			<dd>
			<?php echo h($_SESSION['ticket']['average_budget']); ?>
			</dd>
      <dt>支払い方法</dt>
			<dd>
			<?php echo h($methodOfPayment); ?>
			</dd>
      <dt>その他資格</dt>
			<dd>
			<?php echo h($_SESSION['ticket']['qualified']); ?>
			</dd>
      <dt>施設ジャンル</dt>
			<dd>
			<?php echo h($_SESSION['ticket']['genre']); ?>
			</dd>
      <dt>その他ジャンル</dt>
			<dd>
			<?php echo h($_SESSION['ticket']['other_genre']); ?>
			</dd>
		</dl>
		<div><a href="submit.php?action=rewrite">&laquo;&nbsp;書き直す</a> | <input
		type="submit" value="登録する"></div>
  </form>
  </div>

</div>
</body>
</html>
