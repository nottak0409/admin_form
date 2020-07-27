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

if(!empty($_POST)){
	$fileName = $_FILES['image']['name'];
  if(!empty($fileName)) {
	  $ext = substr($fileName, -3);
	  if ($ext != 'jpg' && $ext != 'gif') {
		$error['image'] = 'jpgかgifの画像ファイルを選択してください。';
	}
}

//投稿内容のエラーチェック
if (empty($error)) {
	$_SESSION['ticket'] = $_POST;
	if (isset($fileName)) {
	$image = date('YmdHis') . $_FILES['image']['name'];
	move_uploaded_file($_FILES['image']['tmp_name'], '/picture/' . $image);
	$_SESSION['ticket']['picture'] = $image;
	}
	header('Location: submit_check.php');
	exit();
 }
}

if(!empty($_POST)) {
	if($_POST['message'] != '') {
		$message = $db->prepare('INSERT INTO posts SET user_id=?, message=?, reply_post_id=?, created=NOW()');
		$message->execute(array(
			$member['id'],
			$_POST['message'],
			$_POST['reply_post_id']
		));
		header('Location: list.php'); exit;
	}
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>投稿</title>

  <link rel="stylesheet" href="../stylesheets/style.css" />
</head>

<body>
<div id="wrap">
  <div id="head">
    <h1>投稿</h1>
  </div>
  <div id="content">
		<dt>投稿者名:<?php echo h($member['name']); ?>さん</dt>
  <form action="" method="post">
		<dl>
			<dt>会社名、イベント名</dt>
			<dd>
				<input type="text" name="name" size="35" maxlength="255" />
			</dd>
			<dt>タイトル</dt>
      <dd>
			  <input type="text" name="title" size="50" maxlength="255" />
			</dd>
			<dt>投稿写真</dt>
			<dd>
				<input type="file" name="picture" size="35" />
        <?php if(isset($error['picture'])): ?>
        <p class="error"><?php echo $error['picture']; ?></p>
        <?php endif; ?>
        <?php if (!empty($error) && isset($_POST['picture'])) : ?>
        <p class="error">恐れ入りますが、もう一度画像を指定してください</p>
        <?php endif; ?>
			</dd>
			<dt>小タイトル</dt>
			<dd>
				<input type="text" name="small_title" size="50" maxlength="255" />
			</dd>
			<dt>内容</dt>
			<dd>
			  <textarea name="content" cols="50" rows="5"></textarea>
			</dd>
			<dt>営業時間</dt>
			<dd>
        <input type="text" name="business_hours" size="30" maxlength="255" />
			</dd>
			<dt>営業内容</dt>
			<dd>
				<textarea name="business_hours_text" cols="50" rows="5"></textarea>
			</dd>
			<dt>住所</dt>
			<dd>
				<input type="text" name="address" size="50" maxlength="255" />
			</dd>
			<dt>電話番号</dt>
			<dd>
				<input type="text" name="tel" size="30" maxlength="255" />
			</dd>
			<dt>平均予算</dt>
			<dd>
				<input type="text" name="average_budget" size="50" maxlength="255" />
			</dd>
			<dt>支払い方法</dt>
			<dd>
			  <input type="checkbox" name="method_of_payment[]" value="現金のみ" />現金のみ
				<input type="checkbox" name="method_of_payment[]" value="クレジットカード可" />クレジット使用可能
				<input type="checkbox" name="method_of_payment[]" value="電子マネー可" />電子マネー使用可能
			</dd>
			<dt>施設ジャンル</dt>
			<dd>
				<input type="text" name="genre" size="50" maxlength="255" />
			</dd>
			<dt>その他ジャンル</dt>
			<dd>
				<textarea name="other_genre" cols="50" rows="5"></textarea>
			</dd>
		</dl>
		<div>
			<input type="submit" value="投稿する" />
		</div>
	</form>
  </div>
</div>
</body>
</html>
