<?php
session_start();

// データベースの接続情報
require("board/database.php");

// タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');

//変数の初期化
$error_message = array();
$register_user_name = null;
$register_user_id = null;
$register_user_password = null;

//ログイン
require("login_check.php");


$register = htmlspecialchars($_GET['register_id'], ENT_QUOTES);

// データベースに接続
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// 文字コード設定
$mysqli->set_charset('utf8');

// ここにデータを取得する処理が入る
$sql = "SELECT * FROM `reset` WHERE `pass_reset` = '$register';";
$res = $mysqli->query($sql);

// データを登録
$register_info = $res->fetch_assoc();

//24時間以内か確認
$limitTime = date("Y/m/d H:i:s", strtotime('-1 day'));
if (strtotime($register_info['time']) <= strtotime($limitTime)) {
  header('Location: ./expired.php');
}

//入力確認
if (isset($_POST["register"])) {
  if (empty($_POST["name"])) {
    $error_message = "d-salonネームを入力してください";
  } else if (empty($_POST["user_id"])) {
    $error_message = "メールアドレスを入力してください";
  } else if (empty($_POST["password"])) {
    $error_message = "パスワードを入力してください";
  } else if (empty($_POST["password_check"])) {
    $error_message = "パスワードを入力してください";
  } else if ($_POST['password'] !== $_POST['password_check']) {
    $error_message = "パスワードが一致しません";
  } 

  //データベースへ新規会員情報登録
  if (!empty($_POST["name"]) && !empty($_POST["user_id"]) && !empty($_POST["password"]) &&  ($_POST['password'] === $_POST['password_check'])) {
    $register_user_name = $_POST["name"];
    $register_user_id = openssl_encrypt($register_info['user_id'],'aes-256-ecb','Sotsuron2023');
    $register_user_password = password_hash($_POST["password"], PASSWORD_BCRYPT);

    // データベースに接続
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // 接続エラーの確認
    if ($mysqli->connect_errno) {
      $error_message = 'データの読み込みに失敗しました。 エラー番号 ' . $mysqli->connect_errno . ' : ' . $mysqli->connect_error;
    } else {

      // SQL文の作成
      $stmt = $mysqli->prepare("INSERT INTO user ( user_id, password, user_name, self_introduction ) VALUES ( ?, ?, ?, '')");

      // パラメータを設定
      $stmt->bind_param('sss', $register_user_id, $register_user_password, $register_user_name);

      // 実行
      $res = $stmt->execute();
      $stmt->close();
    }
    //アイコン登録ページへ
    if ($res) {
      $sql = "DELETE FROM reset WHERE pass_reset = '$register'";
      $res = $mysqli->query($sql);

      $mysqli->close();
      $_SESSION["user_id"] = $register_user_id;
      header('Location: ./icon.php');
    }
    $error_message = 'すでに登録されているメールアドレスです<br><a href="forget.php" class="acount-register">パスワードを忘れた場合</a>';
  }
}
?>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <title>d-salon</title>
  <link rel='stylesheet' href='board/css/style.css' type='text/css' media='all' />
  <link rel='stylesheet' href='board/css/register.css' type='text/css' media='all' />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scale=0">
  <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <link rel="icon" type="image/png" href="board/img/icon.png">
  <link rel="apple-touch-icon" sizes="180x180" href="board/img/app_icon.png">
</head>

<body onLoad="document.form1.name.focus();">
  <div class="d-container">
    <div class="t_container">
      <header>
        <a href="javascript:history.back()" class="back"><i class="fa fa-arrow-left"></i></a>
        <p class="do_tweet">アカウントを作成</p>
      </header>
      <div class="twitter__container" id="container">
        <div class="container">
          <img class="logo" src="img/logo.png">
          <?php if (!empty($error_message)) : ?>
            <div class="error">
              <?php echo $error_message; ?>
            </div>
          <?php endif; ?>
          <form name="form1" method="POST">
            <label for="user_id">d-salonネーム</label>
            <input type="text" name="name" value="<?php if (isset($_POST['name'])) {
                                                    echo $_POST['name'];
                                                  } ?>">
            <label for="user_id">ログインID</label>
            <input type="text" name="user_id" value="<?php if (isset($_POST['user_id'])) {
                                                        echo $_POST['user_id'];
                                                      } else {
                                                        echo $register_info['user_id'];
                                                      } ?>" readonly>
            <label for="user_id">パスワード</label>
            <input type="password" name="password">
            <label for="user_id">パスワード（確認）</label>
            <input type="password" name="password_check">
            <div class="next">
              <input type="submit" name="register" value="登録する">
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>

</html>