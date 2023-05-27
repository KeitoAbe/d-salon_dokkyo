<?php

// データベースの接続情報
require("../board/database.php");

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

session_start();

$id = $_POST['id'];
$token = $_POST['token'];

if ($token === $_SESSION['chat_token']) {

	// データベースに接続
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	// 接続エラーの確認
	if ($mysqli->connect_errno) {
		$error_message[] = 'データの読み込みに失敗しました。 エラー番号 ' . $mysqli->connect_errno . ' : ' . $mysqli->connect_error;
	} else {
		$mysqli->set_charset('utf8');
		$id = $mysqli->real_escape_string($id);
		// ここにデータを取得する処理が入る
		$sql = "DELETE FROM `chat` WHERE id = '$id'";
		$res = $mysqli->query($sql);
		$sql = "DELETE FROM `chat_notification` WHERE id = '$id'";
		$res = $mysqli->query($sql);
		$mysqli->close();
	}
}
