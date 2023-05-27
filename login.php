<?php
session_start();

// データベースの接続情報
require("board/database.php");

// タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');

//変数の初期化
$error_message = array();
$user_info = array();
$check_user_id = null;
$user_number = null;
$user_id = null;
$password = null;
$user_name = null;

//ログインしていたらホームへ
if (isset($_SESSION["user_number"])) {
  $login_url = "index.php";
  header("Location: {$login_url}");
  exit;
}

if (isset($_POST["login"])) {

  $check_user_id = openssl_encrypt($_POST["user_id"],'aes-256-ecb','Sotsuron2023');

  // データベースに接続
  $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

  // 接続エラーの確認
  if ($mysqli->connect_errno) {
    $error_message[] = 'データの読み込みに失敗しました。 エラー番号 ' . $mysqli->connect_errno . ' : ' . $mysqli->connect_error;
  } else {
    $mysqli->set_charset('utf8');
    $check_user_id = $mysqli->real_escape_string($check_user_id);
    // ここにデータを取得する処理が入る
    $sql = "SELECT user_number,user_id,password,user_name FROM user where user_id = '$check_user_id'";
    $res = $mysqli->query($sql);

    if ($res) {
      $user_info = $res->fetch_assoc();
      if ($user_info) {
        //idからユーザの情報取得
        $user_number = $user_info['user_number'];
        $user_id = $user_info['user_id'];
        $password = $user_info['password'];
        $user_name = $user_info['user_name'];
      }
    }

    $mysqli->close();

    if (password_verify($_POST["password"], $password)) {
      //パスワードの確認
      $_SESSION["user_number"] = $user_number;
      $_SESSION["user_id"] = $_POST["user_id"];
      $_SESSION["user_name"] = $user_name;
      // 暗号学的的に安全なランダムなバイナリを生成し、それを16進数に変換することでASCII文字列に変換します
      $toke_byte = openssl_random_pseudo_bytes(16);
      $csrf_token = bin2hex($toke_byte);
      // 変数の初期化
      $mysqli = null;
      $sql = null;
      $res = null;
      // データベースに接続
      $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
      // 接続エラーの確認
      if ($mysqli->connect_errno) {
        $error_message = 'データの読み込みに失敗しました。 エラー番号 ' . $mysqli->connect_errno . ' : ' . $mysqli->connect_error;
      } else {
        $mysqli->set_charset('utf8');
        $csrf_token = $mysqli->real_escape_string($csrf_token);
        $user_number = $mysqli->real_escape_string($user_number);
        // トークンを更新
        $sql = "UPDATE user SET token = '$csrf_token' WHERE user_number ='$user_number'";
        $res = $mysqli->query($sql);
        $mysqli->close();
      }
      setcookie("d_token", "$csrf_token", time() + (20 * 365 * 24 * 60 * 60));
      if (isset($_SESSION['return'])) {
        $login_success_url = $_SESSION['return'];
      } else {
        $login_success_url = "index.php";
      }
      header("Location: {$login_success_url}");
      exit;
    }

    $error_message = "入力されたメールアドレスやパスワードが正しくありません。確認してからやりなおしてください。";
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
        <p class="home">ログイン</p>
      </header>
      <div class="twitter__container" id="container">
        <div class="container">
          <img class="logo" src="img/logo.png">
          <?php if (!empty($error_message)) : ?>
            <div class="error">
              <?php echo $error_message; ?>
            </div>
          <?php endif; ?>
          <form action="login.php" name="form1" method="POST">
            <input type="text" name="user_id" placeholder="メールアドレスまたは学籍番号">
            <input type="password" name="password" placeholder="パスワード">
            <input type="submit" name="login" value="ログイン">
          </form>
          <div class="reset_password">
            <a href="forget.php" class="acount-register">パスワードを忘れた場合</a>
          </div>
          <a href="mail_check.php" class="acount-register">
            <div class="acount">
              新しいアカウント作成する
            </div>
          </a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>