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

if(!empty($_POST)){
	$fileName = $_FILES['picture']['name'];
  if(!empty($fileName)) {
	  $ext = substr($fileName, -3);
	  if ($ext != 'jpg' && $ext != 'gif' && $ext != 'png') {
		$error['picture'] = 'jpegかgifかpngの画像ファイルを選択してください。';
	  }
  }

	if(empty($fileName)){
		$error['picture'] = '画像を登録してください。';
	}

  if(empty($_POST['name'])){
		$error['name'] = "会社名かイベント名を入力してください";
	}

	if(empty($_POST['title'])) {
		$error['title'] = "タイトルを入力してください。";
	}

	if(empty($_POST['small_title'])) {
		$error['small_title'] = "小タイトルを入力してください。";
	}

	if(empty($_POST['content'])) {
		$error['content'] = "内容を入力してください";
	}

	if(empty($_POST['method_of_payment'])){
		$error['method_of_payment'] = "どれか一つ選択してください";
	}

//投稿内容のエラーチェック
if (empty($error)) {
	$_SESSION['ticket'] = $_POST;
	$image = date('YmdHis') . $_FILES['picture']['name'];
	move_uploaded_file($_FILES['picture']['tmp_name'], '../picture/' . $image);
	$_SESSION['ticket']['picture'] = $image;
	header('Location: submit_confirm.php');
	exit();
 }
}

if ($_REQUEST['action'] == 'rewrite') {
	$_POST = $_SESSION['ticket'];
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
		<dt>投稿者名:<?php echo h($user['name']); ?>さん</dt>
  <form action="" method="post" enctype="multipart/form-data">
		<dl>
			<dt>会社名、イベント名</dt>
			<dd>
				<input type="text" name="name" size="35" maxlength="255" value="<?php echo h($_POST['name']); ?>"/>
				<?php if (isset($error['name'])): ?>
				<p class="error"><?php echo $error['name']; ?></p>
			  <?php endif; ?>
			</dd>
			<dt>タイトル</dt>
      <dd>
			  <input type="text" name="title" size="50" maxlength="255" value="<?php echo h($_POST['title']); ?>"/>
				<?php if (isset($error['title'])): ?>
				<p class="error"><?php echo $error['title']; ?></p>
				<?php endif; ?>
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
				<input type="text" name="small_title" size="50" maxlength="255" value="<?php echo h($_POST['small_title']); ?>"/>
				<?php if (isset($error['small_title'])): ?>
				<p class="error"><?php echo $error['small_title']; ?></p>
				<?php endif; ?>
			</dd>
			<dt>内容</dt>
			<dd>
			  <textarea name="content" cols="50" rows="5"><?php echo h($_POST['content']); ?></textarea>
				<?php if (isset($error['content'])): ?>
				<p class="error"><?php echo $error['content']; ?></p>
				<?php endif; ?>
			</dd>
			<dt>営業時間</dt>
			<dd>
        <input type="text" name="business_hours" size="30" maxlength="255" value="<?php echo h($_POST['business_hours']); ?>"/>
			</dd>
			<dt>営業内容</dt>
			<dd>
				<textarea name="business_hours_text" cols="50" rows="5"><?php echo h($_POST['business_hours_text']); ?></textarea>
			</dd>
			<dt>住所</dt>
			<dd>
				<input type="text" name="address" size="50" maxlength="255" value="<?php echo h($_POST['address']); ?>"/>
			</dd>
			<dt>電話番号</dt>
			<dd>
				<input type="text" name="tel" size="30" maxlength="255" value="<?php echo h($_POST['tel']); ?>"/>
			</dd>
			<dt>平均予算</dt>
			<dd>
				<input type="text" name="average_budget" size="50" maxlength="255" value="<?php echo h($_POST['average_budget']); ?>"/>
			</dd>
			<dt>支払い方法</dt>
			<dd>
			  <input type="checkbox" name="method_of_payment[]" value="現金のみ" />現金のみ
				<input type="checkbox" name="method_of_payment[]" value="クレジットカード可" />クレジット使用可能
				<input type="checkbox" name="method_of_payment[]" value="電子マネー可" />電子マネー使用可能
				<?php if (isset($error['method_of_payment'])): ?>
				<p class="error"><?php echo $error['method_of_payment']; ?></p>
				<?php endif; ?>
			</dd>
      <dt>その他の資格</dt>
			<dd>
				<input type="text" name="qualified" size="50" maxlength="255" value="<?php echo h($_POST['qualified']); ?>"/>
			</dd>
			<dt>施設ジャンル</dt>
			<dd>
				<input type="text" name="genre" size="50" maxlength="255" value="<?php echo h($_POST['genre']); ?>"/>
			</dd>
			<dt>その他ジャンル</dt>
			<dd>
				<textarea name="other_genre" cols="50" rows="5"><?php echo h($_POST['other_genre']); ?></textarea>
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
