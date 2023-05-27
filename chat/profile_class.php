<?php

session_start();

//ログイン
require("../board/login_check.php");
$_SESSION['return'] = $_SERVER["REQUEST_URI"];

// データベースの接続情報
require("../board/database.php");

//関数
require("../board/function.php");

// タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');

//プロフィールを開くユーザ取得
$profile = $user_number;

// 変数の初期化
$mysqli = null;
$sql = null;
$res = null;

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$mysqli->set_charset('utf8');
$profile = $mysqli->real_escape_string($profile);
// ここにデータを取得する処理が入る
$sql = "SELECT * FROM user WHERE user_number = '$profile'";
$res = $mysqli->query($sql);

if ($res) {
  $profile_info = $res->fetch_assoc();
}

$mysqli->close();

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($mysqli->connect_errno) {
  $error_message[] = 'データの読み込みに失敗しました。 エラー番号 ' . $mysqli->connect_errno . ' : ' . $mysqli->connect_error;
} else {
  $mysqli->set_charset('utf8');
  $user_number = $mysqli->real_escape_string($user_number);
  // ここにデータを取得する処理が入る
  $sql = "SELECT * FROM register_class INNER JOIN class ON register_class.no = class.no WHERE register_class.user_number = '$user_number';";

  $res = $mysqli->query($sql);

  if ($res) {
    //投稿の取得
    $message_array = $res->fetch_all(MYSQLI_ASSOC);
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
  <link rel='stylesheet' href='../board/css/tweet.css' type='text/css' media='all' />
  <link rel='stylesheet' href='../board/css/profile.css' type='text/css' media='all' />
  <link rel='stylesheet' href='css/profile_class.css' type='text/css' media='all' />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scale=0">
  <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <link rel="icon" type="image/png" href="../board/img/icon.png">
  <link rel="apple-touch-icon" sizes="180x180" href="../board/img/app_icon.png">
</head>

<body>
  <div class="d-container">
    <div class="overlay-white"></div>
    <div class="container">
      <header>
        <a href="../chat/index.php" class="back"><i class="fa fa-arrow-left"></i></a>
        <p class="do_tweet"><?php echo $profile_info['user_name']; ?></p>
      </header>

      <div class="profile">
        <?php if ($user_number === $profile_info['user_number']) { ?>
          <div class="profile_btn">
            <a class="logout_btn" onclick="logout();">ログアウト</a>
          </div>
        <?php } ?>
        <div class="message_icon">
          <img src="../board/icon/<?php echo $profile_info['user_number']; ?>" />
        </div>
        <h1><?php echo $profile_info['user_name'] ?></h1>
      </div>
      <div class="user_profile">
        <p><?php echo $profile_info['self_introduction'] ?></p>
      </div>
      <div class="profile-tab">
      </div>
      <div class="class_list">
        <table class="table table-bordered" id="class">
          <?php foreach ($message_array as $value) { ?>
            <div class="class">
              <i class="fas fa-ellipsis-h btn-menu btn_open btn_<?php echo $value['No']; ?>" onclick="btn_menu(<?php echo $value['No']; ?>);"></i>
              <div class="tweet_delete">
                <div class="slide-menu">
                  <a onclick="no(<?php echo $value['No']; ?>,<?php echo $user_number; ?>);">
                    <p>授業登録を解除</p>
                  </a>
                </div>
              </div>
              <div onclick="chat(<?php echo $value['No']; ?>);">
                <?php echo $value['Semester'] . "　" . $value['Day'] . "　" . $value['CourseAffiliation'] . "<br>" . $value['CourseName'] . "<br>" . $value['Instructor']; ?>
              </div>
            </div>
          <?php } ?>
        </table>
      </div>
    </div>
  </div>
</body>
<script type="text/javascript" src="js/d-salon.js"></script>

</html>