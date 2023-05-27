<?php

//ログイン情報保持
if (isset($_SESSION["user_number"])) {
  $user_number = $_SESSION["user_number"];
} else {
  $user_number = 0;
}
if (isset($_SESSION['user_name'])) {
  $user_name = $_SESSION['user_name'];
} else {
  $user_name = "";
}
if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
} else {
  $user_id = "no_login";
}

if ($user_number == 0) {
  //自動ログイン
  if (isset($_COOKIE["d_token"])) {
    // データベースの接続情報
    require("board/database.php");
    $token = $_COOKIE["d_token"];
    // データベースに接続
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // 接続エラーの確認
    if ($mysqli->connect_errno) {
      $error_message[] = 'データの読み込みに失敗しました。 エラー番号 ' . $mysqli->connect_errno . ' : ' . $mysqli->connect_error;
    } else {
      // ここにデータを取得する処理が入る
      $sql = "SELECT user_number,user_id,password,user_name FROM user where token = '$token'";
      $res = $mysqli->query($sql);

      if ($res) {
        $user_info = $res->fetch_assoc();
        if ($user_info) {
          //トークンからユーザの情報取得
          $_SESSION["user_number"] = $user_info['user_number'];
          $_SESSION['user_id'] = $user_info['user_id'];
          $_SESSION['user_name'] = $user_info['user_name'];
        }
        if (isset($_SESSION["user_number"])) {
          $user_number = $_SESSION["user_number"];
          $user_name = $_SESSION['user_name'];
          $user_id = $_SESSION['user_id'];
        }
      }
    }
  }
}
