<?php
//返信数取得
function reply($reply_value)
{
	// 変数の初期化
	$mysqli = null;
	$sql = null;
	$res = null;
	$reply_count = null;

	// データベースに接続
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$mysqli->set_charset('utf8');
	$reply_value = $mysqli->real_escape_string($reply_value);
	// ここにデータを取得する処理が入る
	$sql = "SELECT count(user_number) FROM message WHERE reply_id = $reply_value";
	$res = $mysqli->query($sql);

	if ($res) {
		$reply_count = $res->fetch_assoc();
	}
	$mysqli->close();
	if ($reply_count['count(user_number)'] > 0) {
		echo $reply_count['count(user_number)'];
	}
}

//いいね数取得
function iine($iine_value1, $iine_value2)
{
	// 変数の初期化
	$mysqli = null;
	$sql = null;
	$res = null;
	$user_id = null;
	$iine_check = array();
	$id = null;

	// データベースに接続
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$mysqli->set_charset('utf8');
	$iine_value1 = $mysqli->real_escape_string($iine_value1);
	$iine_value2 = $mysqli->real_escape_string($iine_value2);
	$sql = "SELECT * FROM likes WHERE id = '$iine_value1' AND user_number = '$iine_value2'";
	$res = $mysqli->query($sql);
	if ($res) {
		$iine_check = $res->fetch_assoc();
	}
	if (isset($iine_check)) {
		echo "active fas";
	}
	$mysqli->close();
}

//返信取得
function get_reply($gr_value)
{

	$reply_data = null;

	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	// 接続エラーの確認
	if ($mysqli->connect_errno) {
		$error_message[] = 'データの読み込みに失敗しました。 エラー番号 ' . $mysqli->connect_errno . ' : ' . $mysqli->connect_error;
	} else {
		// ここにデータを取得する処理が入る
		$mysqli->set_charset('utf8');
		$gr_value = $mysqli->real_escape_string($gr_value);
		$sql = "SELECT * FROM message INNER JOIN user ON message.user_number = user.user_number WHERE id = '$gr_value'";
		$res = $mysqli->query($sql);

		if ($res) {
			$reply_data = $res->fetch_assoc();
		}

		$mysqli->close();
	}
}

//返信取得
function get_message($gr_value)
{

	$reply_data = null;

	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	// 接続エラーの確認
	if ($mysqli->connect_errno) {
		$error_message[] = 'データの読み込みに失敗しました。 エラー番号 ' . $mysqli->connect_errno . ' : ' . $mysqli->connect_error;
	} else {
		// ここにデータを取得する処理が入る
		$mysqli->set_charset('utf8');
		$gr_value = $mysqli->real_escape_string($gr_value);
		$sql = "SELECT * FROM message INNER JOIN user ON message.user_number = user.user_number WHERE id = '$gr_value'";
		$res = $mysqli->query($sql);

		if ($res) {
			$reply_data = $res->fetch_assoc();
		}

		$mysqli->close();
		echo $reply_data['message'];
	}
}

//アイコン画像リサイズ比率計算
function ratio($w, $h)
{
	$newwidth = 0; // 新しい横幅
	$newheight = 0; // 新しい縦幅
	$res_w = 320; // 最大横幅
	$res_h = 320; // 最大縦幅

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
