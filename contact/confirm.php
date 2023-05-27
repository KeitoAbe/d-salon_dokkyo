<?php
session_start();
$opinion = $_POST['opinion'];
$_SESSION['opinion'] = $opinion;

if ($_POST['opinion'] === "") {
    header("Location:index.php");
}
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
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <title>d-salon お問い合わせ</title>
</head>

<body>
    <img src="../img/logo.png" class="logo"></img>
    <div class="opinionForm">
        <h1>以下の内容で送信しますか？</h1>
        <div class="opinion_message">
            <p id="message"><?php echo nl2br(htmlspecialchars($opinion, ENT_QUOTES)); ?></p>
        </div>
        <div class="submitBtn">
            <form method="post" action="./thanks.php">
                <a href="index.php"><button type="button">戻る</button></a>
                <input type="submit" value="送信" id="submit">
            </form>
        </div>
    </div>
</body>
<script src="js/contact.js"></script>

</html>