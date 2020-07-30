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

    if(empty($error)){
     if(($_FILES['picture']['name']) != "") {
        $ext = substr($_POST['picture'], -3);
        if ($ext != 'jpg' && $ext != 'gif' && $ext != 'png') {
        $error['picture'] = 'jpegかgifかpngの画像ファイルを選択してください。';
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
  <div class="body">
  <h1>編集</h1>
  <form action="" method="post" enctype="multipart/form-data">
		<dl>
			<dt>会社名、イベント名</dt>
			<dd>
				<input type="text" name="name" size="35" maxlength="255" value="<?php echo h($post['name']); ?>"/>
				<?php if (isset($error['name'])): ?>
				<p class="error"><?php echo $error['name']; ?></p>
			  <?php endif; ?>
			</dd>
			<dt>タイトル</dt>
      <dd>
			  <input type="text" name="title" size="50" maxlength="255" value="<?php echo h($post['title']); ?>"/>
				<?php if (isset($error['title'])): ?>
				<p class="error"><?php echo $error['title']; ?></p>
				<?php endif; ?>
			</dd>
			<dt>投稿写真</dt>
			<dd>
        <img src="../picture/<?php echo h($post['picture']); ?>" width="200" height="200" /><br />
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
				<input type="text" name="small_title" size="50" maxlength="255" value="<?php echo h($post['small_title']); ?>"/>
				<?php if (isset($error['small_title'])): ?>
				<p class="error"><?php echo $error['small_title']; ?></p>
				<?php endif; ?>
			</dd>
			<dt>内容</dt>
			<dd>
			  <textarea name="content" cols="50" rows="5"><?php echo h($post['content']); ?></textarea>
				<?php if (isset($error['content'])): ?>
				<p class="error"><?php echo $error['content']; ?></p>
				<?php endif; ?>
			</dd>
			<dt>営業時間</dt>
			<dd>
        <input type="text" name="business_hours" size="30" maxlength="255" value="<?php echo h($post['business_hours']); ?>"/>
			</dd>
			<dt>営業内容</dt>
			<dd>
				<textarea name="business_hours_text" cols="50" rows="5"><?php echo h($post['business_hours_text']); ?></textarea>
			</dd>
			<dt>住所</dt>
			<dd>
				<input type="text" name="address" size="50" maxlength="255" value="<?php echo h($post['address']); ?>"/>
			</dd>
			<dt>電話番号</dt>
			<dd>
				<input type="text" name="tel" size="30" maxlength="255" value="<?php echo h($post['tel']); ?>"/>
			</dd>
			<dt>平均予算</dt>
			<dd>
				<input type="text" name="average_budget" size="50" maxlength="255" value="<?php echo h($post['average_budget']); ?>"/>
			</dd>
			<dt>支払い方法</dt>
			<dd>
        <input type="checkbox" name="method_of_payment[]" value="現金のみ" <?php if (preg_match("/現金のみ/", $post['method_of_payment'])): ?> checked="checked" <?php endif; ?>/>現金のみ
        <input type="checkbox" name="method_of_payment[]" value="クレジットカード可" <?php if (preg_match("/クレジットカード可/", $post['method_of_payment'])): ?> checked="checked" <?php endif; ?>/>クレジット使用可能
				<input type="checkbox" name="method_of_payment[]" value="電子マネー可" <?php if (preg_match("/電子マネー可/", $post['method_of_payment'])): ?> checked="checked" <?php endif; ?>/>電子マネー使用可能
				<?php if (isset($error['method_of_payment'])): ?>
				<p class="error"><?php echo $error['method_of_payment']; ?></p>
				<?php endif; ?>
			</dd>
      <dt>その他の資格</dt>
			<dd>
				<input type="text" name="qualified" size="50" maxlength="255" value="<?php echo h($post['qualified']); ?>"/>
			</dd>
			<dt>施設ジャンル</dt>
			<dd>
				<input type="text" name="genre" size="50" maxlength="255" value="<?php echo h($post['genre']); ?>"/>
			</dd>
			<dt>その他ジャンル</dt>
			<dd>
				<textarea name="other_genre" cols="50" rows="5"><?php echo h($post['other_genre']); ?></textarea>
			</dd>
		</dl>
		<div>
			<input type="submit" value="更新する" />
		</div>
	</form>
  </div>
</body>
</html>
