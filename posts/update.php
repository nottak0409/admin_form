<?php
session_start();

require('../function/function.php');
require('../function/dbconnect.php');

if(isset($_SESSION['id'])) {
  $posts = $db->prepare('SELECT users.name, posts.* FROM users, posts WHERE users.id=posts.user_id AND posts.id=? ORDER BY posts.created DESC');
  $posts->execute(array($_REQUEST['id']));
  $post = $posts->fetch();

  if(!empty($_POST) && $post['user_id'] == $_SESSION['id']){

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

    if(($_FILES['picture']['name']) != ""){
    $ext = substr($_FILES['picture']['name'], -3);
    if ($ext != 'jpg' && $ext != 'gif' && $ext != 'png') {
    $error['picture'] = 'jpegかgifかpngの画像ファイルを選択してください。';
    }}

    if(empty($error)){
     if(($_FILES['picture']['name']) != "") {
       $fileName = "../picture/" . $post['picture'];
       if(file_exists($fileName)){
         unlink($fileName);
       }
        $image = date('YmdHis') . $_FILES['picture']['name'];
        move_uploaded_file($_FILES['picture']['tmp_name'], '../picture/' . $image);
        $picture = $db->prepare('UPDATE posts SET picture=? WHERE id=?');
        $picture->execute(array(
        $image,
        $post['id']
        ));
     }
    $methodOfPayment = h(implode(',', $_POST['method_of_payment']));
    $message = $db->prepare('UPDATE posts SET name=?, title=?, small_title=?, content=?, business_hours=?, business_hours_text=?, address=?, tel=?, average_budget=?, method_of_payment=?, qualified=?, genre=?, other_genre=? WHERE id=?');
		$message->execute(array(
      $_POST['name'],
      $_POST['title'],
      $_POST['small_title'],
      $_POST['content'],
      $_POST['business_hours'],
      $_POST['business_hours_text'],
      $_POST['address'],
      $_POST['tel'],
      $_POST['average_budget'],
      $methodOfPayment,
      $_POST['qualified'],
      $_POST['genre'],
      $_POST['other_genre'],
      $post['id']
    ));
    header('Location: list.php'); exit();
    }
  }
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>編集</title>
  <link rel="stylesheet" href="../stylesheets/style.css" />
  <link rel="stylesheet" href="../stylesheets/bootstrap.css" />
</head>
<body>
  <div class="body-submit">
  <h1>編集</h1>
  <form action="" method="post" enctype="multipart/form-data">
  <div class="form-group">
		<label>会社名、イベント名</label>
		<input type="text" name="name" class="form-control" size="35" maxlength="255" value="<?php echo h($post['name']); ?>"/>
		<?php if (isset($error['name'])): ?>
		<p class="error"><?php echo $error['name']; ?></p>
		<?php endif; ?>
	</div>
  <div class="form-group">
		<label>タイトル</label>
		<input type="text" name="title" class="form-control" size="50" maxlength="255" value="<?php echo h($post['title']); ?>"/>
		<?php if (isset($error['title'])): ?>
		<p class="error"><?php echo $error['title']; ?></p>
		<?php endif; ?>
  </div>
  <div class="form-group">
		<label>投稿写真</label><br />
    <img src="../picture/<?php echo h($post['picture']); ?>" width="200" height="200" /><br />
		<input type="file" class="form-control-file picture" name="picture" size="35" />
    <?php if(isset($error['picture'])): ?>
    <p class="error"><?php echo $error['picture']; ?></p>
    <?php endif; ?>
		<?php if (!empty($error) && isset($_POST['picture'])) : ?>
    <p class="error">恐れ入りますが、もう一度画像を指定してください</p>
    <?php endif; ?>
	</div>
  <div class="form-group">
		<label>小タイトル</label>
		<input type="text" name="small_title" class="form-control" size="50" maxlength="255" value="<?php echo h($post['small_title']); ?>"/>
		<?php if (isset($error['small_title'])): ?>
		<p class="error"><?php echo $error['small_title']; ?></p>
		<?php endif; ?>
  </div>
  <div class="form-group">
	  <label>内容</label>
		<textarea name="content" class="form-control" cols="50" rows="5"><?php echo h($post['content']); ?></textarea>
		<?php if (isset($error['content'])): ?>
		<p class="error"><?php echo $error['content']; ?></p>
		<?php endif; ?>
  </div>
  <div class="form-group">
		<label>営業時間</label>
    <input type="text" name="business_hours" class="form-control" size="30" maxlength="255" value="<?php echo h($post['business_hours']); ?>"/>
  </div>
  <div class="form-group">
		<label>営業内容</label>
		<textarea name="business_hours_text" class="form-control" cols="50" rows="5"><?php echo h($post['business_hours_text']); ?></textarea>
	</div>
  <div class="form-group">
		<label>住所</label>
		<input type="text" name="address" class="form-control" size="50" maxlength="255" value="<?php echo h($post['address']); ?>"/>
  </div>
  <div class="form-group">
		<label>電話番号</label>
		<input type="text" name="tel" class="form-control" size="30" maxlength="255" value="<?php echo h($post['tel']); ?>"/>
	</div>
  <div class="form-group">
		<label>平均予算</label>
		<input type="text" name="average_budget" class="form-control" size="50" maxlength="255" value="<?php echo h($post['average_budget']); ?>"/>
	</div>
	<div>支払い方法</div>
    <input type="checkbox" name="method_of_payment[]" value="現金のみ" <?php if (preg_match("/現金のみ/", $post['method_of_payment'])): ?> checked="checked" <?php endif; ?>/>現金のみ
    <input type="checkbox" name="method_of_payment[]" value="クレジットカード可" <?php if (preg_match("/クレジットカード可/", $post['method_of_payment'])): ?> checked="checked" <?php endif; ?>/>クレジット使用可能
		<input type="checkbox" name="method_of_payment[]" value="電子マネー可" <?php if (preg_match("/電子マネー可/", $post['method_of_payment'])): ?> checked="checked" <?php endif; ?>/>電子マネー使用可能
		<?php if (isset($error['method_of_payment'])): ?>
		<p class="error"><?php echo $error['method_of_payment']; ?></p>
		<?php endif; ?>
  <div class="form-group">
    <label>その他の資格</label>
		<input type="text" name="qualified" class="form-control" size="50" maxlength="255" value="<?php echo h($post['qualified']); ?>"/>
  </div>
  <div class="form-group">
		<label>施設ジャンル</label>
		<input type="text" name="genre" class="form-control" size="50" maxlength="255" value="<?php echo h($post['genre']); ?>"/>
	</div>
  <div class="form-group">
		<label>その他ジャンル</label>
		<textarea name="other_genre" class="form-control" cols="50" rows="5"><?php echo h($post['other_genre']); ?></textarea>
	</div>
	<div>
    <button type="submit" class="btn btn-primary">更新する</button>
	</div>
</form>
</div>
</body>
</html>
