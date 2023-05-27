<?php

session_start();

unset($_SESSION['check_doble']);

// データベースの接続情報
require("database.php");

//ログイン
require("../login_check.php");
$_SESSION['return'] = $_SERVER["REQUEST_URI"];

//関数
require("function.php");

// タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');

//現在日時
$today = date("m月d日");
$yesterday = date("m月d日", strtotime('-1 day'));

//現在のページを取得 存在しない場合は1とする
$page = 1;
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
	$page = (int)$_GET['page'];
}
if (!$page) {
	$page = 1;
}

//ページ毎の件数を設定
$row_count = 25;

if (isset($_GET['topic'])) {
	$topic = htmlspecialchars($_GET['topic'], ENT_QUOTES);
}

// 変数の初期化
$now_date = null;
$data = null;
$file_handle = null;
$split_data = null;
$message = array();
$message_array = array();
$error_message = array();
$clean = array();
$file_name = null;

//画像の保存先
$path = './images/';

// データベースに接続
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ((!isset($topic)) or $topic == '全てのTopic') {
	$topic = '全てのTopic';
	if ($mysqli->connect_errno) {
		$error_message[] = 'データの読み込みに失敗しました。 エラー番号 ' . $mysqli->connect_errno . ' : ' . $mysqli->connect_error;
	} else {
		$mysqli->set_charset('utf8');
		$page = $mysqli->real_escape_string($page);
		$row_count = $mysqli->real_escape_string($row_count);
		// ここにデータを取得する処理が入る
		$sql = "SELECT * FROM message INNER JOIN user ON message.user_number = user.user_number WHERE reply_id is NULL ORDER BY post_date DESC LIMIT " . (($page - 1) * $row_count) . ", " . $row_count;
		$res = $mysqli->query($sql);

		if ($res) {
			//投稿の取得
			$message_array = $res->fetch_all(MYSQLI_ASSOC);
		}


	}
	// 変数の初期化
	$sql = null;
	$res = null;
	$post_count = null;


	// ここにデータを取得する処理が入る
	$sql = "SELECT count(user_id) FROM message INNER JOIN user ON message.user_number = user.user_number WHERE reply_id is NULL ORDER BY post_date DESC";
	$res = $mysqli->query($sql);

	if ($res) {
		$post_count = $res->fetch_assoc();
	}

} else {
	// 接続エラーの確認
	if ($mysqli->connect_errno) {
		$error_message[] = 'データの読み込みに失敗しました。 エラー番号 ' . $mysqli->connect_errno . ' : ' . $mysqli->connect_error;
	} else {
		$mysqli->set_charset('utf8');
		$topic = $mysqli->real_escape_string($topic);
		$page = $mysqli->real_escape_string($page);
		$row_count = $mysqli->real_escape_string($row_count);
		// ここにデータを取得する処理が入る
		$sql = "SELECT * FROM message INNER JOIN user ON message.user_number = user.user_number WHERE reply_id is NULL and message.topic = '$topic' ORDER BY post_date DESC LIMIT " . (($page - 1) * $row_count) . ", " . $row_count;
		$res = $mysqli->query($sql);

		if ($res) {
			//投稿の取得
			$message_array = $res->fetch_all(MYSQLI_ASSOC);
		}

		// 変数の初期化
		$sql = null;
		$res = null;
		$post_count = null;

		// データベースに接続
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		$mysqli->set_charset('utf8');
		$topic = $mysqli->real_escape_string($topic);
		// ここにデータを取得する処理が入る
		$sql = "SELECT count(user_id) FROM message INNER JOIN user ON message.user_number = user.user_number WHERE reply_id is NULL and message.topic = '$topic' ORDER BY post_date DESC";
		$res = $mysqli->query($sql);

		if ($res) {
			$post_count = $res->fetch_assoc();
		}

		$mysqli->close();
	}
}

include_once("Paging.php");

//オブジェクトを生成
$pageing = new Paging();
//1ページ毎の表示数を設定
$pageing->count = $row_count;
//全体の件数を設定しhtmlを生成
$pageing->setHtml($post_count['count(user_id)']);
?>
<html lang="ja">

<head>
	<meta charset="UTF-8" />
	<title>d-salon 質問掲示板</title>
	<link rel='stylesheet' href='css/style.css' type='text/css' media='all' />
	<link rel='stylesheet' href='css/post.css' type='text/css' media='all' />
	<link rel='stylesheet' href='css/home.css' type='text/css' media='all' />
	<link rel='stylesheet' href='css/slide.css' type='text/css' media='all' />
	<link href="css/selmodal_home.css" rel="stylesheet">
	<link rel="stylesheet" href="../menu/style.css" type="text/css" />
	<link rel="stylesheet" href="css/paging.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scale=0">
	<link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
	<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-infinitescroll/2.1.0/jquery.infinitescroll.min.js"></script>
	<link rel="icon" type="image/png" href="img/icon.png">
	<link rel="apple-touch-icon" sizes="180x180" href="img/app_icon.png">
	<script>
		$(function(){
		$('.twitter__container').infinitescroll({
		navSelector  : ".paging",
		nextSelector : ".paging a",
		itemSelector : ".twitter__contents",
		maxPage : <?php echo ceil($post_count['count(user_id)']/$row_count); ?>,
		loading: {
			finishedMsg: "",
			msgText: "",
		},
	});
});
	</script>

</head>

<body>
	<header>
		<div class="overlay"></div>
		<div class="wrapper">
		</div>
	</header>
	<div class="container">
		<div class="d-menu">
			<a href="../">
				<img class="logo" src="img/logo.png">
			</a>
			<div class="topic-select">
				<i class="far fa-check-circle"></i>
				<select name="topic" class="selmodal" onchange="location.href='index.php?topic='+value;">
					<i class="far fa-check-circle"></i>
					<option value="全てのTopic" <?php if ($topic == '全てのtopic') {
																			echo 'selected';
																		} ?>>全てのTopic</option>
					<option value="学生生活" <?php if ($topic == '学生生活') {
																	echo 'selected';
																} ?>>学生生活</option>
					<option value="授業・課題・テスト" <?php if ($topic == '授業・課題・テスト') {
																			echo 'selected';
																		} ?>>授業・課題・テスト</option>
					<option value="履修相談" <?php if ($topic == '履修相談') {
																	echo 'selected';
																} ?>>履修相談</option>
					<option value="部活・サークル" <?php if ($topic == '部活・サークル') {
																		echo 'selected';
																	} ?>>部活・サークル</option>
					<option value="就活" <?php if ($topic == '就活') {
																echo 'selected';
															} ?>>就活</option>
					<option value="アルバイト" <?php if ($topic == 'アルバイト') {
																	echo 'selected';
																} ?>>アルバイト</option>
					<option value="趣味・芸能" <?php if ($topic == '趣味・芸能') {
																	echo 'selected';
																} ?>>趣味・芸能</option>
					<option value="美容・ファッション" <?php if ($topic == '美容・ファッション') {
																			echo 'selected';
																		} ?>>美容・ファッション</option>
					<option value="グルメ" <?php if ($topic == 'グルメ') {
																echo 'selected';
															} ?>>グルメ</option>
					<option value="恋愛・人生相談" <?php if ($topic == '恋愛・人生相談') {
																		echo 'selected';
																	} ?>>恋愛・人生相談</option>
					<option value="時事・ニュース" <?php if ($topic == '時事・ニュース') {
																		echo 'selected';
																	} ?>>時事・ニュース</option>
					<option value="その他" <?php if ($topic == 'その他') {
																echo 'selected';
															} ?>>その他</option>
				</select>
			</div>
			<a><img onclick="profile(<?php echo $user_number; ?>)" class="home-icon btn_open" src="icon/<?php echo $user_number; ?>" /></a>
			<div class="overlay"></div>
			<div class="hamburger">
				<span></span>
				<span></span>
				<span></span>
			</div>
			<nav class="globalMenuSp">
				<ul>
					<li><a href="../">ホーム</a></li>
					<li><a href="../about/">d-salonとは</a></li>
					<li><a href="../news/">NEWS</a></li>
					<li><a href="../contact/">お問い合わせ</a></li>
					<?php if ($user_number !== 0) { ?>
						<li><a onclick="logout();">ログアウト</a></li>
					<?php } ?>
				</ul>
			</nav>
		</div>
	</div>
	<div class="twitter__container" id="container">
		<div class="postQuestion">
			<img src="img/dokuta.png" alt="">
			<p>獨協生に聞いてみよう！</p>
			<div class="post">
				<a onclick="post(<?php echo $user_number; ?>)">聞いてみる</a>
			</div>
		</div>
		<div class="twitter__contents">
			<?php if (!empty($message_array)) { ?>
				<?php foreach ($message_array as $value) { ?>
					<div class="twitter__block">
						<a href="tweet.php?message_id=<?php echo $value['id']; ?>"></a>
						<figure>
							<?php if ($value['tokumei'] == 0) { ?>
								<img onclick="location.href='profile.php?user_number=<?php echo $value['user_number']; ?>'" src="icon/<?php echo $value['user_number']; ?>" />
							<?php } else { ?>
								<img src="img/no_set.jpg" alt="">
							<?php } ?>
						</figure>
						<div class="twitter__block-text">
							<div class="name"><?php if ($value['tokumei'] == 0) {
																	echo $value['user_name'];
																} else {
																	echo "匿名さん";
																} ?> </div>
							<div class="date"><?php if ($today === date('m月d日', strtotime($value['post_date']))) {
																	echo "今日";
																} else if ($yesterday === date('m月d日', strtotime($value['post_date']))) {
																	echo "昨日";
																} else {
																	echo date('m月d日', strtotime($value['post_date']));
																} ?></div>
							<div class="text"><?php echo nl2br($value['message']) ?><br>
								<?php if (!empty($value['image'])) { ?><div class="in-pict"><img src="images/<?php echo $value['image']; ?>"></div><?php } ?>
								<?php if ($value['topic'] != "") { ?><div class="topic-display"><?php echo $value['topic']; ?></div><?php } ?>
							</div>
							<div class="action-btn">
								<i class="comment far fa-comment btn" onclick="window.location.href = 'tweet.php?message_id=<?php echo $value['id'] ?>';"> <?php if($value['reply'] != 0){ echo $value['reply'];}; ?></i>
								<i onclick="iine_login(<?php echo $value['id']; ?>,<?php echo $user_number; ?>)" class="heart btn far fa-heart <?php iine($value['id'], $user_number) ?>" id="<?php echo $value['id']; ?>">&nbsp;<?php if ($value['likes'] != 0) {
																																																																																																										echo $value['likes'];
																																																																																																									} ?></i>
							</div>
						</div>
					</div>
				<?php } ?>
			<?php } ?>
		</div>
	</div>
	<div style="display: none;">
		<?php echo $pageing->html; ?>
	</div>
</body>
<script type="text/javascript" src="js/horitter.js"></script>
<script src="js/Jquery.selmodal.js"></script>
<script type="text/javascript" src="js/d-salon.js?ver=1.1"></script>
<script type="text/javascript" src="../menu/script.js"></script>

</html>