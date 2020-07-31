<?php
session_start();

require('../function/dbconnect.php');
require('../function/function.php');

if(isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
	$_SESSION['time'] = time();

	$users = dbConnect()->prepare('SELECT * FROM users WHERE id=?');
	$users->execute(array($_SESSION['id']));
	$user = $users->fetch();
} else {
	header('Location: ../users/login.php'); exit();
}


if($_SESSION['ticket'] != '') {
    $methodOfPayment = implode(',', $_SESSION['ticket']['method_of_payment']);
  if(!empty($_POST)) {
		$message = dbConnect()->prepare('INSERT INTO posts SET user_id=?, name=?, title=?, picture=?, small_title=?, content=?, business_hours=?, business_hours_text=?, address=?, tel=?, average_budget=?, method_of_payment=?, qualified=?, genre=?, other_genre=?, created=NOW()');
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

	<link rel="stylesheet" href="../stylesheets/bootstrap.css" />
	<link rel="stylesheet" href="../stylesheets/style.css" />
</head>

<body>
  <div class="body">
  <h1>投稿確認画面</h1>
　<form action="" method="post">
    <input type="hidden" name="action" value="submit" />
			<div class="form-group">
			  <label>会社名、イベント名</label>
			  <div class="form-control">
			    <?php echo h($_SESSION['ticket']['name']); ?>
		　  </div>
	    </div>
			<div class="form-group">
			  <label>タイトル</label>
			  <div class="form-control">
			    <?php echo h($_SESSION['ticket']['title']); ?>
				</div>
			</div>
			<div class="form-group">
        <label>写真</label><br />
        <img src="../picture/<?php echo h($_SESSION['ticket']['picture']); ?>" width="100" height="100" alt="" />
			</div>
			<div class="form-group">
        <label>小タイトル</label>
        <div class="form-control">
          <?php echo h($_SESSION['ticket']['small_title']); ?>
        </div>
			</div>
			<div class="form-group">
        <label>内容</label>
			  <div class="form-control">
			    <?php echo h($_SESSION['ticket']['content']); ?>
			  </div>
			</div>
			<div class="form-group">
        <label>営業時間</label>
			  <div class="form-control">
			    <?php echo h($_SESSION['ticket']['business_hours']); ?>
				</div>
			</div>
			<div class="form-group">
        <label>営業内容</label>
        <div class="form-control">
          <?php echo h($_SESSION['ticket']['business_hours_text']); ?>
        </div>
			</div>
			<div class="form-group">
        <label>住所</label>
			  <div class="form-control">
			    <?php echo h($_SESSION['ticket']['address']); ?>
			  </div>
			</div>
			<div class="form-group">
        <label>電話番号</label>
			  <div class="form-control">
			    <?php echo h($_SESSION['ticket']['tel']); ?>
			  </div>
			</div>
			<div class="form-group">
        <label>平均予算</label>
			  <div class="form-control">
			    <?php echo h($_SESSION['ticket']['average_budget']); ?>
			  </div>
			</div>
			<div class="form-group">
        <label>支払い方法</label>
			  <?php echo h($methodOfPayment); ?>
			</div>
			<div class="form-group">
        <label>その他資格</label>
			  <div class="form-control">
			    <?php echo h($_SESSION['ticket']['qualified']); ?>
			  </div>
			</div>
			<div class="form-group">
        <label>施設ジャンル</label>
			  <div class="form-control">
			    <?php echo h($_SESSION['ticket']['genre']); ?>
			  </div>
			</div>
			<div class="form-group">
        <label>その他ジャンル</label>
			  <div class="form-control">
			    <?php echo h($_SESSION['ticket']['other_genre']); ?>
			  </div>
			</div>
		<div><a href="submit.php?action=rewrite">&laquo;&nbsp;書き直す</a> | 			<button type="submit" class="btn btn-primary">投稿する</button></div>
  </form>
  </div>
</body>
</html>
