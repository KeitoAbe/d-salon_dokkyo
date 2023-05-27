<?php

//データベース接続情報
require("./board/database.php");

$pass_reset = htmlspecialchars($_GET['pass_reset'], ENT_QUOTES);

// データベースに接続
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// 文字コード設定
$mysqli->set_charset('utf8');

// ここにデータを取得する処理が入る
$pass_reset = $mysqli->real_escape_string($pass_reset);
$sql = "SELECT * FROM `reset` WHERE `pass_reset` = '$pass_reset';";
$res = $mysqli->query($sql);
$user_info = $res->fetch_assoc();

$user_info['user_id'] = ($mysqli->real_escape_string(openssl_encrypt($user_info['user_id'],'aes-256-ecb','Sotsuron2023')));
$sql = "SELECT * FROM `user` WHERE `user_id` = '$user_info[user_id]';";
$res = $mysqli->query($sql);
$is_user = $res->fetch_assoc();

//アカウント登録がされてない場合は新規登録画面へ
if (is_null($is_user)) {
  header("Location: ./register.php?register=$pass_reset");
}

//24時間以内か確認
$limitTime = date("Y/m/d H:i:s", strtotime('-1 day'));
if (strtotime($user_info['time']) <= strtotime($limitTime)) {
  header('Location: ./expired.php');
}

if (!empty($_POST['login'])) {
  if (empty($_POST["password"])) {
    $error_message = "パスワードを入力してください";
  } else if (empty($_POST["password_check"])) {
    $error_message = "パスワードを入力してください";
  } else if ($_POST['password'] !== $_POST['password_check']) {
    $error_message = "パスワードが一致しません";
  } else {
    $register_user_password = password_hash($_POST["password"], PASSWORD_BCRYPT);
    $sql = "UPDATE user SET `password`= '$register_user_password' WHERE user_id ='$user_info[user_id]'";
    $res = $mysqli->query($sql);
    $sql = "DELETE FROM reset WHERE pass_reset = '$pass_reset'";
    $res = $mysqli->query($sql);
    // データベースの接続を閉じる
    $mysqli->close();

    header('Location: ./reset_complete.php');
  }
}

?>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <title>d-salon</title>
  <link rel='stylesheet' href='board/css/style.css' type='text/css' media='all' />
  <link rel='stylesheet' href='board/css/index.css' type='text/css' media='all' />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scale=0">
  <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <link rel="icon" type="image/png" href="img/icon.png">
  <link rel="apple-touch-icon" sizes="180x180" href="img/app_icon.png">
</head>

<body onLoad="document.form1.user_id.focus();">
  <div class="d-container">
    <div class="t_container">
      <header>
        <a href="javascript:history.back()" class="back"><i class="fa fa-arrow-left"></i></a>
        <p class="home">パスワードを再設定</p>
      </header>
      <div class="twitter__container" id="container">
        <div class="container">
          <img class="logo" src="img/logo.png">
          <?php if (!empty($error_message)) : ?>
            <div class="error">
              <?php echo $error_message; ?>
            </div>
          <?php endif; ?>
          <form action="" name="form1" method="POST">
            <label for="user_id">新しいパスワード</label>
            <input type="password" name="password">
            <label for="user_id">新しいパスワード（確認）</label>
            <input type="password" name="password_check">
            <input type="submit" name="login" value="次へ">
          </form>
        </div>
      </div>
    </div>
  </div>
</body>

</html>