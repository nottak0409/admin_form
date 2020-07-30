<?php
require('../function/dbconnect.php');
require('../function/function.php');

session_start();

if ($_COOKIE['email' != '']){
	$_POST['email'] = $_COOKIE['email'];
	$_POST['password'] = $_COOKIE['password'];
	$_POST['save'] = 'on';
}

if(!empty($_POST)) {
	if($_POST['email'] != '' && $_POST['password'] != '') {
		$login = $db->prepare('SELECT * FROM users WHERE email=? AND password=?');
		$login->execute(array(
			$_POST['email'],
			sha1($_POST['password'])
		));
		$member = $login->fetch();

	if($member) {
		$_SESSION['id'] = $member['id'];
		$_SESSION['time'] = time();

		if ($_POST['save'] == 'on') {
			setcookie('email', $_POST['email'], time()+60*60*24*14);
			setcookie('password', $_POST['password'], time()+60*60*24*14);
		}

		header('Location: ../posts/list.php');
		exit();
	} else {
		$error['login'] = "ログインに失敗しました";
	   }
  } else {
		$error['login'] = "入力してください";
	}
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>ログインフォーム</title>
	<link rel="stylesheet" href="../stylesheets/style.css" />
  <link rel="stylesheet" href="../stylesheets/bootstrap.css" />
  </head>
  <body>
	<div class="body">
  <div id="head">
    <h1>ログインする</h1>
  </div>
  <div class="content">
  <p>メールアドレスとパスワードを記入してログインしてください。</p>
	<p>入会手続きがまだの方はこちらからどうぞ。</p>
	<p>&raquo;<a href="register.php">入会手続きをする</a></p>
	</div>
	<form action="" method="post">
		<div class="form-group row">
			<label for="email" class="col-sm-4 col-form-label">メールアドレス</label>
      <div class="col-md-8">
			  <input type="text" class="form-control" name="email" maxlength="255" value="<?php echo h($_POST['email']); ?>"/>
			  <?php if (isset($error['login'])): ?>
			  <p class="error"><?php echo $error['login']; ?></p>
   		<?php endif; ?>
			</div>
		</div>
		  <div class="form-group row">
				<label for="password" class="col-sm-4 col-form-label">パスワード</label>
			  <div class="col-md-8">
			    <input type="text" name="password" class="form-control" maxlength="255" value="<?php echo h($_POST['password']); ?>"/>
			    <?php if (isset($error['login'])): ?>
			    <p class="error"><?php echo $error['login']; ?></p>
			    <?php endif; ?>
				</div>
			</div>
			<div class="form-group row">
			<label for="remember" class="col-sm-4 col-form-label">ログイン情報の記録</label>
			  <div class="col-md-8">
			    <input type="checkbox" class="form-check-input" name="save" value="on" />
			    <label class="form-check-label" for="save">次回からは自動的にログインする</label>
		    </div>
		  </div>
		<button type="submit" class="btn btn-primary">ログインする</button>
	</form>
  </div>
  </body>
  </html>
