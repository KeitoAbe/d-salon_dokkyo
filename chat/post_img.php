<?php

session_start();
$_SESSION['return'] = $_SERVER["REQUEST_URI"];

// データベースの接続情報
require("../board/database.php");

//ログイン
require("../login_check.php");

//アイコン画像リサイズ比率計算
function ratio($w, $h)
{
	$newwidth = 0; // 新しい横幅
	$newheight = 0; // 新しい縦幅
	$res_w = 1000; // 最大横幅
	$res_h = 1000; // 最大縦幅

	if ($w > $h) {
		// 横長の画像は横のサイズを指定値にあわせる
		$ratio = $h / $w;
		$newwidth = $res_w;
		$newheight = $res_w * $ratio;
	} else {
		// 縦長の画像は縦のサイズを指定値にあわせる
		$ratio = $w / $h;
		$newwidth = $res_h * $ratio;
		$newheight = $res_h;
	}


	$rtn_wh = [
		'width' => $newwidth,
		'height' => $newheight,
	];

	return $rtn_wh;
}

//アイコン画像リサイズ
function img_resize($url, $res_w, $res_h, $p_w, $p_h, $x, $y, $picturename)
{
	list($w, $h, $type) = getimagesize($url);

	switch ($type) {
		case IMAGETYPE_JPEG:
			$in = imagecreatefromjpeg($url);
			break;
		case IMAGETYPE_GIF:
			$in = imagecreatefromgif($url);
			break;
		case IMAGETYPE_PNG:
			$in = imagecreatefrompng($url);
			break;
	}
	// コピー画像のリソース
	$out = imagecreatetruecolor($res_w, $res_h);
	imagealphablending($out, false);
	imagesavealpha($out, true);
	// リサイズ
	ImageCopyResampled($out, $in, 0, 0, $x, $y, $res_w, $res_h, $p_w, $p_h);

	imagepng($out, $picturename);

	imagedestroy($out);
	imagedestroy($in);
}

// タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');
$today = date("YmdHis");
$path = './images/';

if (isset($_FILES['file1']['tmp_name'])) {
	if ($_FILES['file1']['name'] !== $_SESSION['doble_check']) {
		move_uploaded_file($_FILES['file1']['tmp_name'], $path . $today . "_" . $_FILES['file1']['name']);
		$file_name = $today . "_" . $_FILES['file1']['name'];
		$file_location = $path . $today . "_" . $_FILES['file1']['name'];
		$_SESSION['doble_check'] = $_FILES['file1']['name'];

		// ファイルのパーミッションを確実に0644に設定する
		chmod($file_location, 0644);

		list($wid, $hei, $type) = getimagesize($file_location);

		if ($wid >= 1000 || $hei >= 1000) {
			$wh = ratio($wid, $hei);
			//アイコン画像をリサイズ
			img_resize($file_location, $wh['width'], $wh['height'], $wid, $hei, 0, 0, $file_location);
		}

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
				$now_date = $mysqli->real_escape_string($now_date);
				$now_time = $mysqli->real_escape_string($now_time);
				$no = $mysqli->real_escape_string(htmlspecialchars($_POST['no'], ENT_QUOTES));
				$user_number = $mysqli->real_escape_string($_SESSION["user_number"]);
				$file_name = $mysqli->real_escape_string($file_name);

				// データを登録するSQL作成
				$sql = "INSERT INTO chat (message, post_date, post_time, no, user_number, image) VALUES ('', '$now_date', '$now_time', '$no', '$user_number', '$file_name')";

				// データを登録
				$res = $mysqli->query($sql);

				// データベースの接続を閉じる
				$mysqli->close();
			}
		}
	}
}
