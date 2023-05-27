<?php

session_start();

//ログイン
require("../login_check.php");
$_SESSION['return'] = $_SERVER["REQUEST_URI"];

// データベースの接続情報
require("database.php");

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
$tokumei = null;

//画像の保存先
$path = './images/';

// タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');

$message_id = (int)htmlspecialchars($_GET['message_id'], ENT_QUOTES);

// データベースに接続
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// 接続エラーの確認
if ($mysqli->connect_errno) {
  $error_message[] = 'データの読み込みに失敗しました。 エラー番号 ' . $mysqli->connect_errno . ' : ' . $mysqli->connect_error;
} else {
  $mysqli->set_charset('utf8');
  $message_id = $mysqli->real_escape_string($message_id);
  // 返信先メッセージ取得
  $sql = "SELECT * FROM message INNER JOIN user ON message.user_number = user.user_number WHERE id = $message_id";
  $res = $mysqli->query($sql);

  if ($res) {
    $message_data = $res->fetch_assoc();
  }

  $mysqli->close();
}

//画像のアップロード
if (!empty($_POST['btn_submit'])) {
  move_uploaded_file($_FILES['file1']['tmp_name'], $path . $_FILES['file1']['name']);
  $file_name = $_FILES['file1']['name'];

  // メッセージの入力チェック
  if (empty($_POST['message'])) {
    $error_message[] = 'メッセージを入力してください。';
  } else {
    $clean['message'] = htmlspecialchars($_POST['message'], ENT_QUOTES);
    //匿名投稿
    if (isset($_POST['tokumei'])) {
      $tokumei = 1;
    } else {
      $tokumei = 0;
    }
  }

  if (empty($error_message)) {
    // データベースに接続
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // 接続エラーの確認
    if ($mysqli->connect_errno) {
      $error_message[] = '書き込みに失敗しました。 エラー番号 ' . $mysqli->connect_errno . ' : ' . $mysqli->connect_error;
    } else {
      // 文字コード設定
      $mysqli->set_charset('utf8');

      // 書き込み日時を取得
      $now_date = date("Y-m-d H:i:s");

      // SQL文の作成
      $stmt = $mysqli->prepare("INSERT INTO message (user_number, message, post_date, likes, image, reply_id, tokumei) VALUES ( ?, ?, ?, '0', ?, ?, ?)");

      // パラメータを設定
      $stmt->bind_param('isssis', $user_number, $clean['message'], $now_date, $file_name, $message_id, $tokumei);

      // 実行
      $res = $stmt->execute();
      $stmt->close();

      if ($res) {
        $_SESSION['success_message'] = 'メッセージをツイートしました。';
      } else {
        $error_message[] = '書き込みに失敗しました。';
      }

      // SQL文の作成
      $stmt = $mysqli->prepare("UPDATE message set reply = reply + 1 WHERE id =  $message_id");

      // 実行
      $res = $stmt->execute();
      $stmt->close();

      // データベースの接続を閉じる
      $mysqli->close();
    }
    header("Location: ./tweet.php?message_id=$message_id");
  }
}
?>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <title>d-salon</title>
  <link rel='stylesheet' href='css/style.css' type='text/css' media='all' />
  <link rel='stylesheet' href='css/slide.css' type='text/css' media='all' />
  <link rel='stylesheet' href='css/reply.css' type='text/css' media='all' />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scale=0">
  <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <link rel="icon" type="image/png" href="img/icon.png">
  <link rel="apple-touch-icon" sizes="180x180" href="img/app_icon.png">
</head>

<body onLoad="document.form1.message.focus();">
  <div class="container">
    <div class="t_container">
      <form method="post" name="form1" action="" enctype="multipart/form-data">
        <header>
          <a href="javascript:history.back()" class="back"><i class="fa fa-arrow-left"></i></a>
          <input class="reply" type="submit" name="btn_submit" value="返信">
        </header>
        <div class="twitter__container" id="container">
          <div class="twitter__contents">
            <div class="twitter__block">
              <a href="javascript:history.back()"></a>
              <figure>
                <?php if ($message_data['tokumei'] == 0) { ?>
                  <img src="icon/<?php echo $message_data['user_number']; ?>" onclick="location.href='profile.php?user_number=<?php echo $message_data['user_number']; ?>'">
                <?php } else { ?>
                  <img src="img/no_set.jpg" alt="">
                <?php } ?>
              </figure>
              <div class="twitter__block-text">
                <div class="name"><?php if ($message_data['tokumei'] == 0) {
                                    echo $message_data['user_name'];
                                  } else {
                                    echo "匿名さん";
                                  } ?></div>
                <div class="date"><?php echo date('m月d日', strtotime($message_data['post_date'])); ?></div>
                <div class="text">
                  <?php echo nl2br($message_data['message']) ?><br>
                  <?php if (!empty($message_data['image'])) { ?>
                    <div class="in-pict">
                      <img src="images/<?php echo $message_data['image']; ?>">
                    </div>
                  <?php } ?>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php if ($message_data['tokumei'] == 0) { ?>
          <div class="reply-line">
            <p>返信先: <a href="profile.php?user_number=<?php echo $message_data['user_number']; ?>"><?php echo $message_data['user_name']; ?></a></p>
          </div>
        <?php } else { ?>
          <div class="reply-line">
            <p>返信先: 匿名さん</a></p>
          </div>
        <?php } ?>
        <div class="message">
          <div class="message_icon">
            <img src="icon/<?php echo $user_number; ?>" onerror="this.src = 'icon/no_set.jpg';">
            <textarea id="message" name="message" placeholder="投稿に返信しよう！" maxlength=2000></textarea>
            <label class="upload-label" style="margin-top:150px;">
              <i class="fa fa-image"></i>
              <input name="file1" id="myImage" type="file" accept="image/*" />
            </label>
            <div class="tokumei-box"><label><input type="checkbox" name="tokumei" value="1">匿名投稿</label></div>
          </div>
          <div class="preview">
            <img id="preview">
          </div>
      </form>
    </div>
  </div>
  </div>
</body>
<script type="text/javascript" src="js/horitter.js"></script>

</html>