<?php

session_start();

//ログイン
require("../login_check.php");
$_SESSION['return'] = $_SERVER["REQUEST_URI"];

// データベースの接続情報
require("database.php");

//関数
require("function.php");

// タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');

//プロフィールを開くユーザ取得
$profile = htmlspecialchars($_GET['user_number'], ENT_QUOTES);

// 変数の初期化
$mysqli = null;
$sql = null;
$res = null;

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
// ここにデータを取得する処理が入る
$mysqli->set_charset('utf8');
$profile = $mysqli->real_escape_string($profile);
$sql = "SELECT * FROM user WHERE user_number = '$profile'";
$res = $mysqli->query($sql);

if ($res) {
  $profile_info = $res->fetch_assoc();
}

$mysqli->close();

// 変数の初期化
$mysqli = null;
$sql = null;
$res = null;

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($user_number === $profile_info['user_number']) {
  $mysqli->set_charset('utf8');
  $profile = $mysqli->real_escape_string($profile);
  // 自分のプロフィールであれば匿名投稿も表示
  $sql = "SELECT * FROM message INNER JOIN user ON message.user_number = user.user_number WHERE reply_id is NULL and message.user_number = '$profile' ORDER BY post_date DESC";
} else {
  $sql = "SELECT * FROM message INNER JOIN user ON message.user_number = user.user_number WHERE reply_id is NULL and message.user_number = '$profile' and message.tokumei = 0 ORDER BY post_date DESC";
}
$res = $mysqli->query($sql);

if ($res) {
  $message_array = $res->fetch_all(MYSQLI_ASSOC);
}

$mysqli->close();

// 変数の初期化
$mysqli = null;
$sql = null;
$res = null;

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($user_number === $profile_info['user_number']) {
  $mysqli->set_charset('utf8');
  $profile = $mysqli->real_escape_string($profile);
  // 自分のプロフィールであれば匿名投稿も表示
  $sql = "SELECT * FROM message INNER JOIN user ON message.user_number = user.user_number WHERE reply_id is NOT NULL and message.user_number = '$profile' ORDER BY post_date DESC";
} else {
  $sql = "SELECT * FROM message INNER JOIN user ON message.user_number = user.user_number WHERE reply_id is NOT NULL and message.user_number = '$profile' and message.tokumei = 0 ORDER BY post_date DESC";
}
$res = $mysqli->query($sql);

if ($res) {
  $message_array_reply = $res->fetch_all(MYSQLI_ASSOC);
}

$mysqli->close();


// 変数の初期化
$mysqli = null;
$sql = null;
$res = null;

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
// いいねした投稿を取得
$mysqli->set_charset('utf8');
$profile_info['user_number'] = $mysqli->real_escape_string($profile_info['user_number']);
$sql = "SELECT * FROM likes INNER JOIN message ON likes.id = message.id INNER JOIN user ON message.user_number = user.user_number WHERE likes.user_number='$profile_info[user_number]' ORDER BY likes.likes_number DESC";
$res = $mysqli->query($sql);

if ($res) {
  $message_array_likes = $res->fetch_all(MYSQLI_ASSOC);
}

$mysqli->close();
?>

<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <title>d-salon</title>
  <link rel='stylesheet' href='css/style.css' type='text/css' media='all' />
  <link rel='stylesheet' href='css/slide.css' type='text/css' media='all' />
  <link rel='stylesheet' href='css/profile.css' type='text/css' media='all' />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scale=0">
  <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <link rel="icon" type="image/png" href="img/icon.png">
  <link rel="apple-touch-icon" sizes="180x180" href="img/app_icon.png">
</head>

<body>
  <div class="overlay"></div>
  <div class="container">
    <div class="t_container">
      <header>
        <a href="javascript:history.back()" class="back"><i class="fa fa-arrow-left"></i></a>
        <p class="do_tweet"><?php echo $profile_info['user_name']; ?></p>
      </header>
      <div class="profile">
        <?php if ($user_number === $profile_info['user_number']) { ?>
          <div class="profile_btn">
            <a href="profile_edit.php" class="profile_btn">プロフィールを編集</a>
          </div>
        <?php } ?>
        <div class="message_icon">
          <img src="icon/<?php echo $profile_info['user_number']; ?>" />
        </div>
        <h1><?php echo $profile_info['user_name'] ?></h1>
      </div>
      <div class="user_profile">
        <p><?php echo $profile_info['self_introduction'] ?></p>
      </div>
      <div class="profile-tab">
        <ul>
          <li onclick="profile_question();">投稿</li>
          <li onclick="profile_reply();">返信</li>
          <li onclick="profile_likes();">いいね</li>
        </ul>
      </div>
      <?php if (!empty($message_array) or !empty($message_array_reply) or !empty($message_array_likes)) { ?>
        <div class="twitter__container" id="container">
          <div class="twitter__contents">
            <div class="message_array_question">
              <?php foreach ($message_array as $value) { ?>
                <div class="twitter__block">
                  <a href="tweet.php?message_id=<?php echo $value['id']; ?>"></a>
                  <figure>
                    <?php if ($value['tokumei'] == 0) { ?>
                      <img onclick="location.href='profile.php?user_number=<?php echo $value['user_number']; ?>'" src="icon/<?php echo $value['user_number']; ?>" />
                    <?php } else { ?>
                      <img src="img/no_set.jpg" alt="">
                    <?php } ?>
                  </figure>
                  <div class="twitter__block-text">
                    <div class="name"><?php if ($value['tokumei'] == 0) {
                                        echo $value['user_name'];
                                      } else {
                                        echo "匿名さん";
                                      } ?> </div>
                    <div class="date"><?php echo date('m月d日', strtotime($value['post_date'])); ?></div>
                    <?php if (!empty($value['reply_id'])) { ?><?php get_reply($value['reply_id']); ?>
                    <div class="reply-to-tweet">
                      <?php if (!empty($reply_data['user_id'])) { ?>
                        <p>返信先: <a href="profile.php?user_number=<?php echo $reply_data['user_number']; ?>"><?php echo nl2br($reply_data['user_name']); ?></a>さん</p>
                      <?php } ?>
                    </div>
                  <?php } ?>
                  <div class="text">
                    <?php echo nl2br($value['message']) ?><br>
                    <?php if (!empty($value['image'])) { ?>
                      <div class="in-pict-reply">
                        <img src="images/<?php echo $value['image']; ?>">
                      </div>
                    <?php } ?>
                  </div>
                  <div class="action-btn">
                    <i class="comment far fa-comment btn" onclick="window.location.href = 'tweet.php?message_id=<?php echo $value['id'] ?>';"> <?php reply($value['id']); ?></i>
                    <i onclick="iine_reply_login(<?php echo $value['id']; ?>,<?php echo $user_number; ?>)" class="heart btn far fa-heart <?php iine($value['id'], $user_number) ?> <?php echo $value['id']; ?>">&nbsp;<?php if ($value['likes'] != 0) {
                                                                                                                                                                                                                    echo $value['likes'];
                                                                                                                                                                                                                  } ?></i>
                  </div>
                  </div>
                </div>
              <?php } ?>
            </div>
            <div class="message_array_reply">
              <?php foreach ($message_array_reply as $value) { ?>
                <div class="twitter__block">
                  <a href="tweet.php?message_id=<?php echo $value['id']; ?>"></a>
                  <figure>
                    <?php if ($value['tokumei'] == 0) { ?>
                      <img onclick="location.href='profile.php?user_number=<?php echo $value['user_number']; ?>'" src="icon/<?php echo $value['user_number']; ?>" />
                    <?php } else { ?>
                      <img src="img/no_set.jpg" alt="">
                    <?php } ?>
                  </figure>
                  <div class="twitter__block-text">
                    <div class="name"><?php if ($value['tokumei'] == 0) {
                                        echo $value['user_name'];
                                      } else {
                                        echo "匿名さん";
                                      } ?> </div>
                    <div class="date"><?php echo date('m月d日', strtotime($value['post_date'])); ?></div>
                    <?php if (!empty($value['reply_id'])) { ?><?php get_reply($value['reply_id']); ?>
                    <div class="reply-to-tweet">
                      <p><?php get_message($value['reply_id']); ?></p>
                    </div>
                  <?php } ?>
                  <div class="text">
                    <?php echo nl2br($value['message']) ?><br>
                    <?php if (!empty($value['image'])) { ?>
                      <div class="in-pict-reply">
                        <img src="images/<?php echo $value['image']; ?>">
                      </div>
                    <?php } ?>
                  </div>
                  <div class="action-btn">
                    <i onclick="iine_reply_login(<?php echo $value['id']; ?>,<?php echo $user_number; ?>)" ; class="heart btn far fa-heart <?php iine($value['id'], $user_number) ?> <?php echo $value['id']; ?>">&nbsp;<?php if ($value['likes'] != 0) {
                                                                                                                                                                                                                      echo $value['likes'];
                                                                                                                                                                                                                    } ?></i>
                  </div>
                  </div>
                </div>
              <?php } ?>
            </div>
            <div class="message_array_likes">
              <?php foreach ($message_array_likes as $value) { ?>
                <div class="twitter__block">
                  <a href="tweet.php?message_id=<?php echo $value['id']; ?>"></a>
                  <figure>
                    <?php if ($value['tokumei'] == 0) { ?>
                      <img onclick="location.href='profile.php?user_number=<?php echo $value['user_number']; ?>'" src="icon/<?php echo $value['user_number']; ?>" />
                    <?php } else { ?>
                      <img src="img/no_set.jpg" alt="">
                    <?php } ?>
                  </figure>
                  <div class="twitter__block-text">
                    <div class="name"><?php if ($value['tokumei'] == 0) {
                                        echo $value['user_name'];
                                      } else {
                                        echo "匿名さん";
                                      } ?> </div>
                    <div class="date"><?php echo date('m月d日', strtotime($value['post_date'])); ?></div>
                    <?php if (!empty($value['reply_id'])) { ?><?php get_reply($value['reply_id']); ?>
                    <div class="reply-to-tweet">
                      <p><?php get_message($value['reply_id']); ?></p>
                    </div>
                  <?php } ?>
                  <div class="text">
                    <?php echo nl2br($value['message']) ?><br>
                    <?php if (!empty($value['image'])) { ?>
                      <div class="in-pict-reply">
                        <img src="images/<?php echo $value['image']; ?>">
                      </div>
                    <?php } ?>
                  </div>
                  <div class="action-btn">
                    <?php if (empty($value['reply_id'])) { ?>
                      <i class="comment far fa-comment btn" onclick="window.location.href = 'tweet.php?message_id=<?php echo $value['id'] ?>';"> <?php reply($value['id']); ?></i>
                    <?php } ?>
                    <i onclick="iine_reply_login(<?php echo $value['id']; ?>,<?php echo $user_number; ?>)" ; class="heart btn far fa-heart <?php iine($value['id'], $user_number) ?> <?php echo $value['id']; ?>">&nbsp;<?php if ($value['likes'] != 0) {
                                                                                                                                                                                                                      echo $value['likes'];
                                                                                                                                                                                                                    } ?></i>
                  </div>
                  </div>
                </div>
              <?php } ?>
            </div>
          <?php } ?>
          </div>
        </div>
    </div>
    <div id="wrapper">
      <div id="slide">
        <div class="slide-menu">
          <a href="delete.php?message_id=<?php echo $message_data['id']; ?>">
            <p><i class="fas fa-trash-alt"></i>　ホリートを削除</p>
          </a>
          <div class="cancel">
            <button class="btn-menu btn_close">キャンセル</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
<script type="text/javascript" src="js/horitter.js"></script>
<script type="text/javascript" src="js/d-salon.js"></script>

</html>