<?php

session_start();
//ログイン
require("../login_check.php");

// データベースの接続情報
require("../board/database.php");

// タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');

// 変数の初期化
$now_date = null;
$data = null;
$file_handle = null;
$split_data = null;
$message = array();
$message_array = array();
$error_message = array();
$clean = array();
$file_name = null;

//写真の保存場所
$path = './images/';

$no = (int)htmlspecialchars($_GET['no'], ENT_QUOTES);

// データベースに接続
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// 接続エラーの確認
if ($mysqli->connect_errno) {
  $error_message[] = 'データベースの接続に失敗しました。 エラー番号 ' . $mysqli->connect_errno . ' : ' . $mysqli->connect_error;
} else {
  $mysqli->set_charset('utf8');
  $no = $mysqli->real_escape_string($no);
  // いいねしているユーザーを取得
  $sql = "SELECT * FROM register_class join user on register_class.user_number = user.user_number WHERE no = $no";
  $res = $mysqli->query($sql);

  if ($res) {
    $likes = $res->fetch_all(MYSQLI_ASSOC);
  }

  $mysqli->close();
}

?>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <title>d-salon</title>
  <link rel='stylesheet' href='../board/css/style.css' type='text/css' media='all' />
  <link rel='stylesheet' href='../board/css/slide.css' type='text/css' media='all' />
  <link rel='stylesheet' href='../board/css/likes.css' type='text/css' media='all' />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scale=0">
  <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <link rel="icon" type="image/png" href="../board/img/icon.png">
  <link rel="apple-touch-icon" sizes="180x180" href="../board/img/app_icon.png">
</head>

<body>
  <div class="t_container">
    <header>
      <a href="javascript:history.back()" class="back"><i class="fa fa-arrow-left"></i></a>
      <p class="home">メンバー</p>
    </header>
    <div class="twitter__container" id="container">
      <div class="twitter__contents">
        <?php if (!empty($likes)) { ?>
          <?php foreach ($likes as $value) { ?>
            <div class="twitter__block">
              <figure>
                <img src="../board/icon/<?php echo $value['user_number']; ?>" / style="cursor: initial;">
              </figure>
              <p class="who_tweet"><?php if (!empty($value['user_name'])) {
                                      echo $value['user_name'];
                                    } ?></p>
              <p class="self-introduction"><?php echo $value['self_introduction']; ?></p>
            </div>
          <?php } ?>
        <?php } ?>
      </div>
    </div>
  </div>
</body>

</html>