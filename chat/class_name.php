<?php
// データベースの接続情報
require("../board/database.php");

// タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');

session_start();

$no = $_POST['no'];

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($mysqli->connect_errno) {
  $error_message[] = 'データの読み込みに失敗しました。 エラー番号 ' . $mysqli->connect_errno . ' : ' . $mysqli->connect_error;
} else {
  $mysqli->set_charset('utf8');
  $no = $mysqli->real_escape_string($no);
  // ここにデータを取得する処理が入る
  $sql = "SELECT * FROM `class` WHERE `no` =$no";

  $res = $mysqli->query($sql);

  if ($res) {
    //投稿の取得
    $class_array = $res->fetch_assoc();
  }

  $mysqli->close();
  echo (json_encode($class_array));
}
