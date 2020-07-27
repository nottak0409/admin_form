<?php
session_start();

require('../function/dbconnect.php');
require('../function/function.php');

if(isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
	$_SESSION['time'] = time();

	$members = $db->prepare('SELECT * FROM users WHERE id=?');
	$members->execute(array($_SESSION['id']));
	$member = $members->fetch();
} else {
	header('Location: ../users/login.php'); exit();
}


$page = $_REQUEST['page'];
if ($page == '') {
	$page = 1;
}

$page = max($page, 1);

$counts = $db->query('SELECT COUNT(*) AS cnt FROM posts');
$cnt = $counts->fetch();
$maxPage = ceil($cnt['cnt'] / 5);
$page = min($page, $maxPage);
$start = ($page - 1) * 5;

$posts = $db->prepare('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id ORDER BY p.created DESC LIMIT ?, 5');
$posts->bindParam(1, $start, PDO::PARAM_INT);
$posts->execute();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>一覧画面</title>

  <link rel="stylesheet" href="../stylesheets/style.css" />
</head>

<body>
<div id="wrap">
	<a href="submit.php">投稿される方はこちら</a>
  <div id="head">
    <h1>一覧</h1>
  </div>
  <div id="content">
	<?php foreach ($posts as $post): ?>
		<div class="msg">
			<p><?php echo makeLink(h($post['message'])); ?><span class="name">(<?php echo h($post['name']); ?>)</span>
			<p class="day"><a href="view.php?id=<?php echo h($post['id']); ?>"><?php echo h($post['created']); ?></a>
			<?php if($_SESSION['id'] == $post['user_id']): ?>
      [<a href="delete.php?id=<?php echo h($post['id']); ?>" style="color:#F33;">削除</a>]
		  <?php endif; ?>
			</p>
		</div>
	<?php endforeach; ?>
	<ul class="paging">
		<?php if($page > 1): ?>
			<li><a href="list.php?page=<?php print($page - 1); ?>">前のページへ</a></li>
		<?php else: ?>
			<li>前のページへ</li>
		<?php endif; ?>
		<?php if($page < $maxPage): ?>
			<li><a href="list.php?page=<?php print($page + 1); ?>">次のページへ</a></li>
		<?php else: ?>
			<li>次のページへ</li>
		<?php endif; ?>
	</ul>
	<div class="logout" style="text-align: center; padding-top: 10px;"><a href="logout.php">ログアウト</a></div>
	</div>
  </div>
</div>
</body>
</html>
