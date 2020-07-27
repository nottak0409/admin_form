<?php
session_start();

require('../function/dbconnect.php');
require('../function/function.php');

if (empty($_REQUEST['id'])) {
	header('Location: list.php'); exit();
}

$posts = $db->prepare('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id AND p.id=? ORDER BY p.created DESC');
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
    <h1>会員登録</h1>
  </div>
  <div id="content">
  <p>&laquo;<a href="list.php">一覧に戻る</a></p>
	<?php	if ($post = $posts->fetch()):	?>
	<div class="msg">
	<img src="picture/<?php echo h($post['picture']); ?>" width="48" height="48" alt="<?php echo h($post['name']); ?>" />
	<p><?php echo h($post['message']); ?><span class-"name">(<?php echo h($post['name']); ?>) </span></p>
	<p class="day"><?php echo h($post['created']); ?></p>
	</div>
  <?php else: ?>
		<p>その投稿は削除されたか、URLが間違えています。</p>
	<?php endif; ?>
  </div>

</div>
</body>
</html>