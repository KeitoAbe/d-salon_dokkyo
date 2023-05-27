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

$no = 1;

// データベースに接続
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// 接続エラーの確認
if ($mysqli->connect_errno) {
	$error_message[] = 'データの読み込みに失敗しました。 エラー番号 ' . $mysqli->connect_errno . ' : ' . $mysqli->connect_error;
} else {
	$mysqli->set_charset('utf8');
	$no = $mysqli->real_escape_string($no);
	// ここにデータを取得する処理が入る
	$sql = "SELECT * FROM openchat INNER JOIN user ON openchat.user_number = user.user_number where no = '$no' ORDER BY id ASC";
	$res = $mysqli->query($sql);

	if ($res) {
		$message_array = $res->fetch_all(MYSQLI_ASSOC);
	}
	$mysqli->close();
	for ($i = 0; $i < count($message_array); $i++) {
		//削除実行
		unset($message_array[$i]['password']);
		unset($message_array[$i]['token']);
		unset($message_array[$i]['user_id']);
		unset($message_array[$i]['self_introduction']);
	}
	echo (json_encode($message_array));
}
