<?php
// データベースの接続情報
require("../board/database.php");

// タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');

session_start();

$no = $_POST['no'];
$user_number = $_POST['user_number'];

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($mysqli->connect_errno) {
  $error_message[] = 'データの読み込みに失敗しました。 エラー番号 ' . $mysqli->connect_errno . ' : ' . $mysqli->connect_error;
} else {
  $mysqli->set_charset('utf8');
  $user_number = $mysqli->real_escape_string($user_number);
  $sql = "SELECT * FROM register_class WHERE no = '$no' AND user_number = '$user_number'";
  $res = $mysqli->query($sql);
  if ($res) {
    $register_check = $res->fetch_assoc();
  }
  if (!isset($register_check)) {
    $mysqli->set_charset('utf8');
    $no = $mysqli->real_escape_string($no);
    $user_number = $mysqli->real_escape_string($user_number);
    // 授業noとuser_numberを登録
    $sql = "INSERT INTO register_class (no ,user_number) VALUES ( '$no','$user_number')";
    $res = $mysqli->query($sql);
  } else {
    echo (json_encode('e'));
  }

  $mysqli->close();
}
