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

if(!empty($_POST)){
	$fileName = $_FILES['picture']['name'];
  if(!empty($fileName)) {
	  $ext = substr($fileName, -3);
	  if ($ext != 'jpg' && $ext != 'gif' && $ext != 'png') {
		$error['picture'] = 'jpegかgifかpngの画像ファイルを選択してください。';
	  }
  }
	$methodOfPayment = implode(',', $_POST['method_of_payment']);

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

	<link rel="stylesheet" href="../stylesheets/bootstrap.css" />
  <link rel="stylesheet" href="../stylesheets/style.css" />
</head>

<body>
  <div class="body-submit">
		<h1>投稿</h1>
		<dt>投稿者名:<?php echo h($user['name']); ?>さん</dt>
  <form action="" method="post" enctype="multipart/form-data">
			<div class="form-group">
			<label for="name">会社名、イベント名</label>
				<input type="text" name="name" class="form-control" size="35" maxlength="255" value="<?php echo h($_POST['name']); ?>"/>
				<?php if (isset($error['name'])): ?>
				<p class="error"><?php echo $error['name']; ?></p>
			  <?php endif; ?>
			</div>
			<div class="form-group">
			<label>タイトル</label>
			  <input type="text" name="title" class="form-control" size="50" maxlength="255" value="<?php echo h($_POST['title']); ?>"/>
				<?php if (isset($error['title'])): ?>
				<p class="error"><?php echo $error['title']; ?></p>
				<?php endif; ?>
			</div>
			<div class="form-group">
			<label>投稿写真</label>
				<input type="file" name="picture" class="form-control-file picture" size="35" />
        <?php if(isset($error['picture'])): ?>
        <p class="error"><?php echo $error['picture']; ?></p>
        <?php endif; ?>
				<?php if (!empty($error) && isset($_POST['picture'])) : ?>
        <p class="error">恐れ入りますが、もう一度画像を指定してください</p>
        <?php endif; ?>
			</div>
			<div class="form-group">
			<label>小タイトル</label>
				<input type="text" name="small_title" class="form-control" size="50" maxlength="255" value="<?php echo h($_POST['small_title']); ?>"/>
				<?php if (isset($error['small_title'])): ?>
				<p class="error"><?php echo $error['small_title']; ?></p>
				<?php endif; ?>
			</div>
			<div class="form-group">
			<label>内容</label>
			  <textarea name="content" class="form-control" cols="50" rows="5"><?php echo h($_POST['content']); ?></textarea>
				<?php if (isset($error['content'])): ?>
				<p class="error"><?php echo $error['content']; ?></p>
				<?php endif; ?>
			</div>
			<div class="form-group">
			<label>営業時間</label>
        <input type="text" name="business_hours" class="form-control" size="30" maxlength="255" value="<?php echo h($_POST['business_hours']); ?>"/>
			</div>
			<div class="form-group">
			<label>営業内容</label>
				<textarea name="business_hours_text" class="form-control" cols="50" rows="5"><?php echo h($_POST['business_hours_text']); ?></textarea>
			</div>
			<div class="form-group">
			<label>住所</label>
				<input type="text" name="address" class="form-control" size="50" maxlength="255" value="<?php echo h($_POST['address']); ?>"/>
			</div>
			<div class="form-group">
			<label>電話番号</label>
				<input type="text" name="tel" class="form-control" size="30" maxlength="255" value="<?php echo h($_POST['tel']); ?>"/>
			</div>
			<div class="form-group">
			<label>平均予算</label>
				<input type="text" name="average_budget" class="form-control" size="50" maxlength="255" value="<?php echo h($_POST['average_budget']); ?>"/>
			</div>
			<div>支払い方法</div>
			  <input type="checkbox" class="form-check-input" name="method_of_payment[]" value="現金のみ" <?php if (preg_match("/現金のみ/", $methodOfPayment)): ?> checked="checked" <?php endif; ?>/> 現金のみ
				<input type="checkbox" name="method_of_payment[]" value="クレジットカード可" <?php if (preg_match("/クレジットカード可/", $methodOfPayment)): ?> checked="checked" <?php endif; ?>/>クレジット使用可能
				<input type="checkbox" name="method_of_payment[]" value="電子マネー可" <?php if (preg_match("/電子マネー可/", $methodOfPayment)): ?> checked="checked" <?php endif; ?>/>電子マネー使用可能
				<?php if (isset($error['method_of_payment'])): ?>
				<p class="error"><?php echo $error['method_of_payment']; ?></p>
				<?php endif; ?>
			<div class="form-group">
      <label>その他の資格</label>
				<input type="text" name="qualified" class="form-control" size="50" maxlength="255" value="<?php echo h($_POST['qualified']); ?>"/>
			</div>
			<div class="form-group">
			<label>施設ジャンル</label>
				<input type="text" name="genre" class="form-control" size="50" maxlength="255" value="<?php echo h($_POST['genre']); ?>"/>
			</div>
			<div class="form-group">
			<label>その他ジャンル</label>
				<textarea name="other_genre" class="form-control" cols="50" rows="5"><?php echo h($_POST['other_genre']); ?></textarea>
			</div>
		<div>
			<button type="submit" class="btn btn-primary">確認画面へ</button>
		</div>
	</form>
  </div>
</body>
</html>
