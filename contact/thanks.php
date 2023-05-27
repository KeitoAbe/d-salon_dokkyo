<?php
session_start();
$opinion = $_SESSION['opinion'];
unset($_SESSION['opinion']);

// データベースの接続情報
require("../board/database.php");

// データベースに接続
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// 文字コード設定
$mysqli->set_charset('utf8');

$opinion = $mysqli->real_escape_string($opinion);
// データを登録するSQL作成
$sql = "INSERT INTO `contact`(`opinion`) VALUES ('$opinion');";

// データを登録
$res = $mysqli->query($sql);

// データベースの接続を閉じる
$mysqli->close();

//メール送信
$to = "dsalon.dokkyo@gmail.com";
$subject = "意見が届きました";
$message = $opinion;
$headers = "From: dsalon.dokkyo@gmail.com";
mb_language('Japanese');
mb_internal_encoding('UTF-8');
mb_send_mail($to, $subject, $message, $headers);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="robots" content="noindex">
    <link rel="stylesheet" href="../activity/zemi/gaikokugo.css" type="text/css" />
    <link rel="stylesheet" href="style.css" type="text/css" />
    <title>d-salon お問い合わせ</title>
</head>

<body>
    <img src="../img/logo.png" class="logo"></img>
    <h1>入力内容を送信しました。<br>ありがとうございました。</h1>
    <div class="submitBtn">
        <a href="../"><button class="toppage">ホームへ</button></a>
    </div>
</body>

</html>