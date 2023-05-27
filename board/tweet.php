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

//現在日時
$today = date("m月d日");
$yesterday = date("m月d日", strtotime('-1 day'));

$message_id = (int)htmlspecialchars($_GET['message_id'], ENT_QUOTES);

// データベースに接続
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// 接続エラーの確認
if ($mysqli->connect_errno) {
  $error_message[] = 'データの読み込みに失敗しました。 エラー番号 ' . $mysqli->connect_errno . ' : ' . $mysqli->connect_error;
  var_dump($error_message);
} else {
  $mysqli->set_charset('utf8');
  $message_id = $mysqli->real_escape_string($message_id);
  // ここにデータを取得する処理が入る
  $sql = "SELECT * FROM message INNER JOIN user ON message.user_number = user.user_number WHERE id = $message_id";
  $res = $mysqli->query($sql);

  if ($res) {
    $message_data = $res->fetch_assoc();
  }

  $mysqli->close();
}

// 変数の初期化
$mysqli = null;
$sql = null;
$res = null;
$user_id = null;
$iine_check = array();
$id = null;

// データベースに接続
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$mysqli->set_charset('utf8');
$message_data['id'] = $mysqli->real_escape_string($message_data['id']);
$user_number = $mysqli->real_escape_string($user_number);
$sql = "SELECT * FROM likes WHERE id = '$message_data[id]' AND user_number = '$user_number'";
$res = $mysqli->query($sql);
if ($res) {
  $iine_check = $res->fetch_assoc();
}
// ここにデータを取得する処理が入る
$mysqli->set_charset('utf8');
$message_data['id'] = $mysqli->real_escape_string($message_data['id']);
$sql = "SELECT * FROM message INNER JOIN user ON message.user_number = user.user_number WHERE reply_id = $message_data[id] ORDER BY post_date ASC";
$res = $mysqli->query($sql);

if ($res) {
  $message_array = $res->fetch_all(MYSQLI_ASSOC);
}

$mysqli->close();
?>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <title>d-salon</title>
  <link rel='stylesheet' href='css/style.css' type='text/css' media='all' />
  <link rel='stylesheet' href='css/tweet.css' type='text/css' media='all' />
  <link rel='stylesheet' href='css/slide.css' type='text/css' media='all' />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scale=0">
  <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <link rel="icon" type="image/png" href="img/icon.png">
  <link rel="apple-touch-icon" sizes="180x180" href="img/app_icon.png">
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>

<body>
  <div class="overlay-white"></div>
  <div class="container">
    <div class="t_container">
      <header>
        <a href="javascript:history.back()" class="back"><i class="fa fa-arrow-left"></i></a>
        <p class="do_tweet">投稿</p>
      </header>
      <div class="message">
        <div class="tweet-message">
          <div class="tweet-name">
            <div class="message_icon">
              <?php if ($message_data['tokumei'] == 0) { ?>
                <img onclick="location.href='profile.php?user_number=<?php echo $message_data['user_number']; ?>'" src="icon/<?php echo $message_data['user_number']; ?>" />
              <?php } else { ?>
                <img src="img/no_set.jpg" alt="">
              <?php } ?>
            </div>
            <p class="who_tweet"><?php if ($message_data['tokumei'] == 0) {
                                    echo $message_data['user_name'];
                                  } else {
                                    echo "匿名さん";
                                  } ?></p>
            <i class="fas fa-ellipsis-h btn-menu btn_open"></i>
            <div class="tweet_delete">
              <div class="slide-menu">
                <?php if ($user_number === $message_data['user_number']) { ?>
                  <a onclick="delete_confirm(<?php echo $message_data['id'];?>,<?php echo $message_data['reply_id'];?>)">
                    <p><i class="fas fa-trash-alt"></i>　投稿を削除</p>
                  </a>
                <?php } else { ?>
                  <a onclick="report_confirm(<?php echo $message_data['id']; ?>)">
                    <p><i class="fas fa-ban"></i>　投稿を通報</p>
                  </a>
                <?php } ?>
              </div>
            </div>
          </div>
          <?php if (!empty($message_data['reply_id'])) { ?>
            <?php

            $reply_data = null;

            $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

            // 接続エラーの確認
            if ($mysqli->connect_errno) {
              $error_message[] = 'データの読み込みに失敗しました。 エラー番号 ' . $mysqli->connect_errno . ' : ' . $mysqli->connect_error;
            } else {
              $mysqli->set_charset('utf8');
              $message_data['reply_id'] = $mysqli->real_escape_string($message_data['reply_id']);
              // ここにデータを取得する処理が入る
              $sql = "SELECT * FROM message INNER JOIN user ON message.user_number = user.user_number WHERE id = $message_data[reply_id]";
              $res = $mysqli->query($sql);

              if ($res) {
                $reply_data = $res->fetch_assoc();
              }

              $mysqli->close();
            }

            ?>
            <div class="reply-to">
              <?php if (!empty($reply_data['user_id'])) { ?>
                <p><a href="tweet.php?message_id=<?php echo $message_data['reply_id'] ?>"><?php echo nl2br($reply_data['message']); ?></a>
                <p>
                <?php } ?>
            </div>
          <?php } ?>
          <div class="tweet-message">
            <p><?php if (!empty($message_data['message'])) {
                  echo nl2br($message_data['message']);
                } ?></p>
          </div>
          <?php if (!empty($message_data['image'])) { ?>
            <div class="in-pict">
              <img src="images/<?php echo $message_data['image']; ?>">
            </div>
          <?php } ?>
          <?php if ($message_data['topic'] != "") { ?>
            <div class="topic-container">
              <?php echo $message_data['topic']; ?>
            </div>
          <?php } ?>
          <div class="tweet-date">
            <p><?php echo date('Y年m月d日 H:i', strtotime($message_data['post_date'])); ?></p>
          </div>
          <div class="icon">
            <div id="good">
              <?php if (!empty($message_data['likes'])) { ?>
                <div class="iine">
                  <a href="likes.php?message_id=<?php echo $message_data['id']; ?>">
                    <p><span><?php echo $message_data['likes']; ?></span>件のいいね</p>
                  </a>
                </div>
              <?php } ?>
            </div>
            <div class="icon-end">
              <i onclick="t_iine_login(<?php echo $message_data['id']; ?>,<?php echo $user_number; ?>)" ; class="btn far fa-heart <?php if (isset($iine_check)) {
                                                                                                                                  echo "active fas";
                                                                                                                                } ?>" id="<?php echo $message_data['id']; ?>"></i>
            </div>
          </div>
        </div>
      </div>
      <?php if (!empty($message_array)) { ?>
        <div class="twitter__container" id="container">
          <div class="twitter__contents">
            <?php foreach ($message_array as $value) { ?>
              <div class="twitter__block">
                <a href="tweet.php?message_id=<?php echo $value['id']; ?>"></a>
                <figure>
                  <?php if ($value['tokumei'] == 0) { ?>
                    <img onclick="location.href='profile.php?user_number=<?php echo $value['user_number']; ?>'" src="icon/<?php echo $value['user_number']; ?>">
                  <?php } else { ?>
                    <img src="img/no_set.jpg" alt="">
                  <?php } ?>
                </figure>
                <div class="twitter__block-text">
                  <div class="name"><?php if ($value['tokumei'] == 0) {
                                      echo $value['user_name'];
                                    } else {
                                      echo "匿名さん";
                                    } ?></div>
                  <div class="date"><?php if ($today === date('m月d日', strtotime($value['post_date']))) {
																	echo "今日";
																} else if ($yesterday === date('m月d日', strtotime($value['post_date']))) {
																	echo "昨日";
																} else {
																	echo date('m月d日', strtotime($value['post_date']));
																} ?></div>
                  <?php if (!empty($value['reply_id'])) { ?><?php get_reply($value['reply_id']); ?>
                  <div class="reply-to-tweet">
                    <?php if (!empty($reply_data['user_id'])) { ?>
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
                  <i onclick="iine_reply_login(<?php echo $value['id']; ?>,<?php echo $user_number; ?>)" ; class="heart btn far fa-heart <?php iine($value['id'], $user_number) ?> <?php echo $value['id']; ?>">&nbsp;<?php if ($value['likes'] != 0) {
                                                                                                                                                                                                                    echo $value['likes'];
                                                                                                                                                                                                                  } ?></i>
                </div>
                </div>
              </div>
            <?php } ?>
          <?php } ?>
          </div>
        </div>
    </div>
    <div class="do-reply">
      <?php if (empty($reply_data['user_id'])) { ?>
        <a onclick="reply(<?php echo $message_data['id']; ?>,<?php echo $user_number; ?>)">返信する</a>
      <?php } ?>
    </div>
  </div>
</body>
<script type="text/javascript" src="js/horitter.js"></script>
<script type="text/javascript" src="js/d-salon.js"></script>

</html>