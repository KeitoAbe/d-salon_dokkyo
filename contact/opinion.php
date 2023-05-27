<?php

// データベースの接続情報
require("../board/database.php");

// データベースに接続
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// 文字コード設定
$mysqli->set_charset('utf8');

// ここにデータを取得する処理が入る
$sql = "SELECT * FROM `contact`;";
$res = $mysqli->query($sql);

// データを登録
$message_array = $res->fetch_all(MYSQLI_ASSOC);

// データベースの接続を閉じる
$mysqli->close();

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="robots" content="noindex">
    <link rel="stylesheet" href="../activity/zemi/gaikokugo.css" type="text/css" />
    <link rel="stylesheet" href="style.css" type="text/css" />
    <title>d-salon お問い合わせ</title>
</head>

<body>
    <h1>ご意見一覧</h1>
    <?php foreach ($message_array as $value) { ?>
        <div class="opinion_message">
            <p><?php echo nl2br($value['opinion']); ?></p>
        </div>
    <?php } ?>
</body>

</html>