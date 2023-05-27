<?php
// データベースの接続情報
require("../board/database.php");

session_start();

// タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');

//ログイン
require("../login_check.php");

$message = $_POST['message'];
$no = $_POST['no'];
$user_name = $_SESSION['user_name'];
$user_number = $_SESSION['user_number'];

$token = $_POST['token'];
if ($token === $_SESSION['chat_token']) {

	// データベースに接続
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	// 接続エラーの確認
	if ($mysqli->connect_errno) {
		$error_message[] = '書き込みに失敗しました。 エラー番号 ' . $mysqli->connect_errno . ' : ' . $mysqli->connect_error;
	} else {
		// 文字コード設定
		$mysqli->set_charset('utf8');

		// 書き込み日時を取得
		$week = [
			'日', //0
			'月', //1
			'火', //2
			'水', //3
			'木', //4
			'金', //5
			'土', //6
		];

		$date = date('w');
		$now_date = date("n/j(") . $week[$date] . ")";
		$now_time = date("G:i");
		$mysqli->set_charset('utf8');
		$message = $mysqli->real_escape_string($message);
		$now_date = $mysqli->real_escape_string($now_date);
		$now_time = $mysqli->real_escape_string($now_time);
		$no = $mysqli->real_escape_string($no);
		$user_number = $mysqli->real_escape_string($user_number);

		if (!isset($_POST['reply_id'])) {
			// データを登録するSQL作成
			$sql = "INSERT INTO chat (message, post_date, post_time, no, user_number) VALUES ('$message', '$now_date', '$now_time', '$no', '$user_number')";
		} else {
			$reply_user = $_POST['reply_user'];
			$reply_to = $_POST['reply_to'];
			$reply_to_message = $_POST['reply_to_message'];
			$reply_id = $_POST['reply_id'];
			$reply_user = $mysqli->real_escape_string($reply_user);
			$reply_to = $mysqli->real_escape_string($reply_to);
			$reply_to_message = $mysqli->real_escape_string($reply_to_message);
			$reply_id = $mysqli->real_escape_string($reply_id);
			$sql = "INSERT INTO chat (message, post_date, post_time, no, user_number, reply_user, reply_to, reply_to_message, reply_id) VALUES ('$message', '$now_date', '$now_time', '$no', '$user_number', '$reply_user', '$reply_to', '$reply_to_message', '$reply_id')";
		}
		// データを登録
		$res = $mysqli->query($sql);

		// データベースの接続を閉じる
		$mysqli->close();
	}
}
