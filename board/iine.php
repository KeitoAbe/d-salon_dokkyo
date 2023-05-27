<?php

// データベースの接続情報
require("database.php");

// タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');

// 変数の初期化
$message_id = null;
$mysqli = null;
$sql = null;
$res = null;
$reply_user = null;
$user_id = null;
$iine_check = array();
$id = null;

session_start();

$user_number = $_POST['user_number'];
$message_id = (int)htmlspecialchars($_POST['message_id'], ENT_QUOTES);

// データベースに接続
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$mysqli->set_charset('utf8');
$message_id = $mysqli->real_escape_string($message_id);
$user_number = $mysqli->real_escape_string($user_number);
$sql = "SELECT * FROM likes WHERE id = '$message_id' AND user_number = '$user_number'";
$res = $mysqli->query($sql);
if ($res) {
	$iine_check = $res->fetch_assoc();
}
if (!isset($iine_check)) {
	//いいねされてないときはいいねする
	$sql = "INSERT INTO likes (id, user_number) VALUES ( '$message_id', '$user_number')";
	$res = $mysqli->query($sql);
	$sql = "UPDATE message set likes = likes + 1 WHERE id =  $message_id";
	$res = $mysqli->query($sql);
	$sql = "SELECT likes FROM message WHERE id = '$message_id'";
	$res = $mysqli->query($sql);
	$count = $res->fetch_assoc();
	echo $count['likes'];
} else {
	//いいねされているときはいいねを外す
	$sql = "DELETE FROM likes WHERE id = '$message_id' AND user_number = '$user_number'";
	$res = $mysqli->query($sql);
	$sql = "UPDATE message set likes = likes - 1 WHERE id =  $message_id";
	$res = $mysqli->query($sql);
	$sql = "SELECT likes FROM message WHERE id = '$message_id'";
	$res = $mysqli->query($sql);
	$count = $res->fetch_assoc();
	echo $count['likes'];
}
$mysqli->close();
