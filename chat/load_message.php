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

$no = $_POST['no'];

// データベースに接続
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// 接続エラーの確認
if ($mysqli->connect_errno) {
	$error_message[] = 'データの読み込みに失敗しました。 エラー番号 ' . $mysqli->connect_errno . ' : ' . $mysqli->connect_error;
} else {
	$mysqli->set_charset('utf8');
	$no = $mysqli->real_escape_string($no);
	// ここにデータを取得する処理が入る
	$sql = "SELECT * FROM chat INNER JOIN user ON chat.user_number = user.user_number where no = '$no' ORDER BY id ASC";
	$res = $mysqli->query($sql);

	if ($res) {
		$message_array = $res->fetch_all(MYSQLI_ASSOC);
	}

	for ($i = 0; $i < count($message_array); $i++) {
		//削除実行
		unset($message_array[$i]['password']);
		unset($message_array[$i]['token']);
		unset($message_array[$i]['user_id']);
		unset($message_array[$i]['self_introduction']);
	}

	echo (json_encode($message_array));

	$user_number = $mysqli->real_escape_string($_POST['user_number']);
	foreach ($message_array as $value) {
		$sql = "SELECT * FROM `chat_notification` WHERE id = '$value[id]' and user_number = '$user_number'";
		$res = $mysqli->query($sql);
		$check_read = $res->fetch_assoc();
		if (!isset($check_read)) {
			// ここにデータを取得する処理が入る
			$value['id'] = $mysqli->real_escape_string($value['id']);
			$sql = "INSERT INTO `chat_notification`(`id`, `no`, `user_number`) VALUES ($value[id],$value[no],$user_number)";
			$res = $mysqli->query($sql);
		}
	}

	$mysqli->close();
}
