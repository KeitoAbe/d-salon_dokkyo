<?php

//データベース接続情報
require("database.php");

//メッセージid取得
$message_id = (int)htmlspecialchars($_GET['message_id'], ENT_QUOTES);
$reply_id = (int)htmlspecialchars($_GET['reply_id'], ENT_QUOTES);

// データベースに接続
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// 接続エラーの確認
if ($mysqli->connect_errno) {
	$error_message[] = 'データベースの接続に失敗しました。 エラー番号 ' . $mysqli->connect_errno . ' : ' . $mysqli->connect_error;
} else {
	$mysqli->set_charset('utf8');
	$message_id = $mysqli->real_escape_string($message_id);
	//投稿削除
	$sql = "DELETE FROM message WHERE id = $message_id";
	$res = $mysqli->query($sql);
	$sql = "DELETE FROM likes WHERE id = $message_id";
	$res = $mysqli->query($sql);
	$sql = "UPDATE message set reply = reply - 1 WHERE id =  $reply_id";
	$res = $mysqli->query($sql);
}

$mysqli->close();

// 更新に成功したら一覧に戻る
if ($res) {
	header("Location: ./index.php");
}
