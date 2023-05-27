<?php

session_start();

//ログイン
require("../login_check.php");
$_SESSION['return'] = $_SERVER["REQUEST_URI"];

// データベースの接続情報
require("database.php");

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
$tokumei = null;
$topic = null;

//画像の保存先
$path = './images/';

$date = date("YmdHis");

//画像をアップロード
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
  if (empty($error_message) && ($_SESSION['check_doble']) !== $clean['message']) {
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
      $stmt = $mysqli->prepare("INSERT INTO message (user_number, message, post_date, likes, image, reply_id, tokumei, topic) VALUES ( ?, ?, ?, '0', ?, null, ?, ? )");

      // パラメータを設定
      $stmt->bind_param('isssss', $user_number, $clean['message'], $now_date, $file_name, $tokumei, $_POST['topic']);

      // 実行
      $res = $stmt->execute();
      $stmt->close();

      if ($res) {
        $_SESSION['success_message'] = 'メッセージをツイートしました。';
      } else {
        $error_message[] = '書き込みに失敗しました。';
      }

      // データベースの接続を閉じる
      $mysqli->close();
    }
    $_SESSION['check_doble'] = $clean['message'];
    header('Location: ./index.php');
  } else {
    header('Location: ./index.php');
  }
}

?>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <title>d-salon</title>
  <link rel='stylesheet' href='css/style.css' type='text/css' media='all' />
  <link rel='stylesheet' href='css/post.css' type='text/css' media='all' />
  <link rel='stylesheet' href='css/slide.css' type='text/css' media='all' />
  <link href="css/selmodal.css" rel="stylesheet">
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
          <a href="index.php" class="back"><i class="fa fa-arrow-left"></i></a>
          <input class="tweet" type="submit" name="btn_submit" value="投稿する">
          <div class="overlay"></div>
          <div class="wrapper">
          </div>
        </header>
        <div class="message">
          <div class="message_icon">
            <img src="icon/<?php echo $user_number; ?>" />
          </div>
          <textarea id="message" name="message" placeholder="獨協生に聞いてみよう！" maxlength=2000></textarea>
          <label class="upload-label">
            <i class="fa fa-image"></i>
            <input name="file1" id="myImage" type="file" accept="image/*" />
          </label>
          <div class="tokumei-box"><label><input type="checkbox" name="tokumei" value="1">匿名投稿</label></div>
          <div class="topic-select">
            <select name="topic" class="selmodal">
              <option value="">Topicを選択</option>
              <option value="趣味・芸能">趣味・芸能</option>
              <option value="学生生活">学生生活</option>
              <option value="授業・課題・テスト">授業・課題・テスト</option>
              <option value="履修相談">履修相談</option>
              <option value="部活・サークル">部活・サークル</option>
              <option value="アルバイト">アルバイト</option>
              <option value="美容・ファッション">美容・ファッション</option>
              <option value="グルメ">グルメ</option>
              <option value="恋愛・人生相談">恋愛・人生相談</option>
              <option value="就活">就活</option>
              <option value="時事・ニュース">時事・ニュース</option>
              <option value="その他">その他</option>
            </select>
          </div>
          <div class="preview">
            <img id="preview">
          </div>
      </form>
    </div>
  </div>
</body>
<script type="text/javascript" src="js/horitter.js"></script>
<script src="js/Jquery.selmodal.js"></script>
<script type="text/javascript" src="js/d-salon.js"></script>

</html>