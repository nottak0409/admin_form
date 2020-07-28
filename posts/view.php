<?php
session_start();

require('../function/dbconnect.php');
require('../function/function.php');

if (empty($_REQUEST['id'])) {
	header('Location: list.php'); exit();
}

$posts = $db->prepare('SELECT users.name, posts.* FROM users, posts WHERE users.id=posts.user_id AND posts.id=? ORDER BY posts.created DESC');
$posts->execute(array($_REQUEST['id']));
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>詳細</title>

  <link rel="stylesheet" href="../stylesheets/style.css" />
</head>

<body>
<div id="wrap">
  <div id="head">
    <h1>詳細</h1>
  </div>
  <div id="content">
  <p>&laquo;<a href="list.php">一覧に戻る</a></p>
	<?php	if ($post = $posts->fetch()):	?>
	<div class="content">
	<p><span class-"name"><?php echo h($post['name']); ?></span></p>
	<img src="../picture/<?php echo h($post['picture']); ?>" width="200" height="200" />
	<p><?php echo h($post['small_title']); ?></p>
	<p><?php echo makeLink(h($post['content'])); ?></p>
	<p>営業時間:<?php echo h($post['business_hours']); ?></p>
	<p>営業内容:<?php echo makeLink(h($post['business_hours_text'])); ?></p>
	<p>住所:<?php echo h($post['address']); ?></p>
	<p>電話番号:<?php echo h($post['tel']); ?></p>
	<p>平均予算:<?php echo h($post['average_budget']); ?></p>
	<p>支払い方法:<?php echo h($post['method_of_payment']); ?></p>
	<p>その他の資格:<?php echo h($post['qualified']); ?></p>
	<p>施設ジャンル:<?php echo h($post['genre']); ?></p>
	<p>その他ジャンル:<?php echo h($post['other_genre']); ?></p>
	<p class="day">投稿日時:<?php echo h($post['created']); ?></p>
	</div>
  <?php else: ?>
		<p>その投稿は削除されたか、URLが間違えています。</p>
	<?php endif; ?>
  </div>

</div>
</body>
</html>
