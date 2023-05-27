<?php
session_start();

// データベースの接続情報
require("database.php");

//関数
require("function.php");

// タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');

$error_message = array();
$path = './icon/';

//ログイン
require("../login_check.php");
$_SESSION['return'] = $_SERVER["REQUEST_URI"];

// 変数の初期化
$mysqli = null;
$sql = null;
$res = null;

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$mysqli->set_charset('utf8');
$user_number = $mysqli->real_escape_string($user_number);
// ここにデータを取得する処理が入る
$sql = "SELECT * FROM user WHERE user_number = '$user_number'";
$res = $mysqli->query($sql);

if ($res) {
  $profile_info = $res->fetch_assoc();
}

$mysqli->close();

if (isset($_POST["save"])) {

  if (empty($_POST["name"])) {
    $error_message = "d-salonネームを入力してください";
  }

  if (!empty($_POST["name"])) {
    if (isset($_FILES['image']) && ($_FILES['image']['error'] === 0)) {

      move_uploaded_file($_FILES['image']['tmp_name'], $path . $user_number);

      // ファイルのパーミッションを確実に0644に設定する
      chmod($path . $user_number, 0644);

      list($wid, $hei, $type) = getimagesize($path . $user_number);

      if ($wid >= 320 || $hei >= 320) {
        $wh = ratio($wid, $hei);
        //アイコン画像をリサイズ
        img_resize($path . $user_number, $wh['width'], $wh['height'], $wid, $hei, 0, 0, $path . $user_number);
      }
    }

    $register_user_name = $_POST["name"];
    $register_self_introduction = $_POST["self-introduction"];
    $register_mail_address = openssl_encrypt($_POST["mail"],'aes-256-ecb','Sotsuron2023');

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
      // プロフィール更新
      // SQL文の作成
      $stmt = $mysqli->prepare("UPDATE user SET user_id = ?, user_name = ?, self_introduction= ? WHERE user_number = ?");

      // パラメータを設定
      $stmt->bind_param('sssi',$register_mail_address, $register_user_name, $register_self_introduction, $user_number);

      // 実行
      $res = $stmt->execute();
      $stmt->close();

      $mysqli->close();
    }
    if ($res) {
      $_SESSION["user_name"] = $register_user_name;
      header("Location: ./profile.php?user_number=$profile_info[user_number]");
    }
  }
}

?>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <title>d-salon</title>
  <link rel='stylesheet' href='css/style.css' type='text/css' media='all' />
  <link rel='stylesheet' href='css/slide.css' type='text/css' media='all' />
  <link rel='stylesheet' href='css/profile_edit.css' type='text/css' media='all' />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scale=0">
  <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <link rel="icon" type="image/png" href="img/icon.png">
  <link rel="apple-touch-icon" sizes="180x180" href="img/app_icon.png">
</head>

<body onLoad="document.form1.name.focus();">
  <div class="t_container">
    <form name="form1" method="POST" enctype="multipart/form-data">
      <header>
        <a href="javascript:history.back()" class="back"><i class="fa fa-arrow-left"></i></a>
        <p class="home">プロフィールを編集</p>
        <input class="save" type="submit" name="save" value="保存">
      </header>
      <div class="container">
        <?php if (!empty($error_message)) : ?>
          <div class="error">
            <?php echo $error_message; ?>
          </div>
        <?php endif; ?>
        <label class="upload-label">
          <div class="icon">
            <img id="preview" src="icon/<?php echo $profile_info['user_number']; ?>" / onerror="this.src = 'img/no_set.jpg';">
          </div>
          <input name="image" id="myProfile" type="file" accept="image/*" />
        </label>
        <label for="user_name">d-salonネーム</label>
        <input type="text" name="name" value="<?php echo $profile_info['user_name']; ?>">
        <label for="self-introduction">自己紹介</label>
        <textarea id="self-introduction" name="self-introduction"><?php if (isset($profile_info['self_introduction'])) {
                                                                    echo $profile_info['self_introduction'];
                                                                  } ?></textarea>
        <label for="user_id">ログインID（メールアドレスまたは学籍番号）</label>
        <input type="text" name="mail" value="<?php echo openssl_decrypt($profile_info['user_id'],'aes-256-ecb','Sotsuron2023'); ?>">
      </div>
    </form>
  </div>
</body>
<script type="text/javascript" src="js/horitter.js"></script>
<script type="text/javascript" src="js/icon.js"></script>

</html>