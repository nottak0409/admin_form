<?php
session_start();
require('../function/dbconnect.php');
require('../function/function.php');

if(!empty($_POST)){
	if($_POST['name'] == "") {
		$error['name'] = "入力してください";
	}
	if($_POST['email'] == "") {
		$error['email'] = "入力してください";
	}

  if(preg_match("/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/", $_POST['email']) == 0) {
    $error['email'] = 'メールアドレスの形式が正しくありません。';
  }

	if(strlen($_POST['password']) < 4) {
		$error['password'] = "4文字以上で入力してください";
	}
	if($_POST['password'] == ""){
		$error['password'] = "入力してください";
	}

  if (empty($error)) {
		$user = dbConnect()->prepare('SELECT COUNT(*) AS cnt FROM users WHERE email=?');
		$user->execute(array($_POST['email']));
		$record = $user->fetch();
		if ($record['cnt'] > 0) {
			$error['email'] = '指定されたメールアドレスは既に登録されています。';
		}
	}
	if (empty($error)) {
		$_SESSION['join'] = $_POST;
		header('Location: check.php');
		exit();
	}
	if ($_REQUEST['action'] == 'rewrite') {
		$_POST = $_SESSION['join'];
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
	<link rel="stylesheet" href="../stylesheets/bootstrap.css" />
</head>

<body>
  <div class="body">
	<h1>会員登録</h1>
  <p>次のフォームに必要事項をご記入ください</p>
	<form action="" method="post" enctype="multipart/form-data">
		<div class="form-group row">
			<label for="name">ニックネーム　　<span class="required">必須</span></label>
			<div class="col-md-8">
			  <input type="text" name="name" class="form-control" size="35" maxlength="255" value="<?php echo h($_POST['name']); ?>" />
			  <?php if (isset($error['name'])): ?>
			  <p class="error"><?php echo $error['name']; ?></p>
		    <?php endif; ?>
			</div>
		</div>
		<div class="form-group row">
			<label for="email">メールアドレス　<span class="required">必須</span></label>
			<div class="col-md-8">
			  <input type="text" name="email" class="form-control" size="35" maxlength="255" value="<?php echo h($_POST['email']); ?>"/>
			  <?php if (isset($error['email'])): ?>
			  <p class="error"><?php echo $error['email']; ?></p>
			  <?php endif; ?>
		  </div>
		</div>
		<div class="form-group row">
			<label for="password">パスワード　　　<span class="required">必須</span></label>
      <div class="col-md-8">
  			<input type="password" name="password" class="form-control" size="10" maxlength="20" value="<?php echo h($_POST['password']); ?>"/>
	   		<?php if (isset($error['password'])): ?>
			  <p class="error"><?php echo $error['password']; ?></p>
			  <?php endif; ?>
		  </div>
		</div>
		<button type="submit" class="btn btn-primary">登録する</button>
	</form>
  </div>
</body>
</html>
