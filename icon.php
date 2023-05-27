<?php

session_start();

//ログイン
require("login_check.php");
$_SESSION['return'] = $_SERVER["REQUEST_URI"];

//データベース接続情報
require("board/database.php");

//関数
require("board/function.php");

//アイコンの保存先
$path = './board/icon/';

$check_user_id = $_SESSION["user_id"];

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
// 文字コード設定
$mysqli->set_charset('utf8');

// ここにデータを取得する処理が入る
$check_user_id = $mysqli->real_escape_string($check_user_id);
$sql = "SELECT user_number,user_id,password,user_name FROM user where user_id = '$check_user_id'";
$res = $mysqli->query($sql);

if ($res) {
  $user_info = $res->fetch_assoc();
  $_SESSION["user_number"]  = $user_info['user_number'];
  $_SESSION["user_id"]  = $user_info['user_id'];
  $_SESSION["user_name"]  = $user_info['user_name'];
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
    // トークンを更新
    $sql = "UPDATE user SET token = '$csrf_token' WHERE user_number ='$user_number'";
    $res = $mysqli->query($sql);
    $mysqli->close();
  }
  setcookie("d_token", "$csrf_token", time() + (20 * 365 * 24 * 60 * 60));
}
//アイコンのアップロード
if (!empty($_POST['register'])) {
  if (isset($_FILES['image']) && ($_FILES['image']['error'] === 0)) {
    move_uploaded_file($_FILES['image']['tmp_name'], $path . $_SESSION["user_number"]);

    // ファイルのパーミッションを確実に0644に設定する
    chmod($path . $_SESSION["user_number"], 0644);

    list($wid, $hei, $type) = getimagesize($path . $_SESSION["user_number"]);

    if ($wid >= 320 || $hei >= 320) {
      $wh = ratio($wid, $hei);
      //アイコン画像をリサイズ
      img_resize($path . $user_number, $wh['width'], $wh['height'], $wid, $hei, 0, 0, $path . $_SESSION["user_number"]);
    }

    $login_success_url = $_SESSION['return'];
    header("Location: ./index.php");
  }
  $error_message = "画像が選択されていません";
}
//アイコンを登録しない場合
if (!empty($_POST['no_register'])) {
  copy('board/img/no_set.jpg', $path . $_SESSION["user_number"]);
  header("Location: ./index.php");
}

?>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <title>d-salon</title>
  <link rel='stylesheet' href='board/css/style.css' type='text/css' media='all' />
  <link rel='stylesheet' href='board/css/icon.css' type='text/css' media='all' />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scale=0">
  <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <link rel="icon" type="image/png" href="board/img/icon.png">
  <link rel="apple-touch-icon" sizes="180x180" href="board/img/app_icon.png">
</head>

<body onLoad="document.form1.name.focus();">
  <div class="d-container">
    <div class="t_container">
      <div class="twitter__container" id="container">
        <div class="container">
          <img class="logo" src="board/img/logo.png">
          <h2>プロフィール画像を選ぶ</h2>
          <?php if (!empty($error_message)) : ?>
            <div class="error">
              <?php echo $error_message; ?>
            </div>
          <?php endif; ?>
          <form name="form1" method="POST" action="" enctype="multipart/form-data">
            <label class="upload-label">
              <div class="icon">
                <div class="add">
                  <img src="board/img/add.png">
                </div>
                <img id="preview" src="board/img/no_set.jpg">
              </div>
              <input name="image" id="myProfile" type="file" accept="image/*" />
            </label>
            <div class="next">
              <input type="submit" name="register" value="次へ">
              <input type="submit" name="no_register" value="今はしない">
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
<script type="text/javascript" src="board/js/icon.js"></script>

</html>