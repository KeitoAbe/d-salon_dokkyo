<?php
session_start();
// データベースの接続情報
require("./board/database.php");

//ip取得
$ip = $_SERVER['REMOTE_ADDR'];

//獨協内ネットワークかチェック
if (preg_match('/202.252.105./',$ip) || preg_match('/202.209.27./',$ip)){
  $is_dokkyo = true;
} else {
  $is_dokkyo = false;
}

$pattern = "/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/iD";

if ( !preg_match($pattern, $_POST['user_id'] )) {
  $_SESSION['error_message'] = "正しいメールアドレスを入力してください";
  header('Location: ./mail_check.php');
} else if ($is_dokkyo == false && !preg_match('/@dokkyo.ac.jp/', $_POST['user_id'] )){
  $_SESSION['error_message'] = "獨協大学外のネットワークから接続している方は、獨協大学メールアドレスのみ登録できます。<br>g+学籍番号下7桁+@dokkyo.ac.jpです。";
  header('Location: ./mail_check.php');
}else {
  $mail_address = $_POST['user_id'];
  // 暗号学的的に安全なランダムなバイナリを生成し、それを16進数に変換することでASCII文字列に変換します
  $toke_byte = openssl_random_pseudo_bytes(16);
  $pass_reset = bin2hex($toke_byte);

  // データベースに接続
  $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  // 文字コード設定
  $mysqli->set_charset('utf8');
  $mail_address = $mysqli->real_escape_string($mail_address);

  date_default_timezone_set('Asia/Tokyo');
  $time = date("Y/m/d H:i:s");

  // データを登録するSQL作成
  $sql = "INSERT INTO `reset`(`user_id`, `pass_reset`, `time`) VALUES ('$mail_address','$pass_reset','$time');";
  // データを登録
  $res = $mysqli->query($sql);
  // データベースの接続を閉じる
  $mysqli->close();

  //メール送信
  $to = $mail_address;
  $subject = "d-salon アカウントの作成";
  $message = "以下のURLよりアカウントの作成をお願いします。\r\n\r\nhttps://d-salon.jp/register.php?register_id=$pass_reset\r\n\r\n------------------------------\r\n獨協大学 堀江ゼミd-salon制作班\r\ndsalon.dokkyo@gmail.com\r\nhttps://d-salon.jp/";
  $headers = "From:" . mb_encode_mimeheader("d-salon") . "<dsalon.dokkyo@gmail.com>";
  mb_language('Japanese');
  mb_internal_encoding('UTF-8');
  mb_send_mail($to, $subject, $message, $headers);
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
        <p class="home">アカウントを作成</p>
      </header>
      <div class="twitter__container" id="container">
        <div class="container">
          <img class="logo" src="img/logo.png">
          <p>あなたのメールアドレス：<b><?php echo $mail_address; ?></b>へ確認メールを送信しました。<br>メールの案内に従って、アカウントの作成をお願いします。</p>
          <?php if (preg_match('/@dokkyo.ac.jp/', $_POST['user_id'] )) { ?>
          <div class="btn_container">
            <a target="_blank" href="https://webmail.dokkyo.ac.jp/cgi-bin/index.cgi"><button class="toppage">獨協大学Webメールへ</button></a>
          </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</body>

</html>