<?php
session_start();
if (isset($_SESSION['opinion'])) {
    $opinion = $_SESSION['opinion'];
    unset($_SESSION['opinion']);
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../activity/zemi/gengo.css" type="text/css" />
    <link rel="stylesheet" href="style.css" type="text/css" />
    <link rel="stylesheet" href="icomoon/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../menu/style.css" type="text/css" />
    <title>NEWS</title>
</head>

<body>
    <div class="wrapper">

        <a href="../"><img src="../img/logo.png" class="logo"></img></a>
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
            <li><a href="#">NEWS</a></li>
            <li><a href="../contact/">お問い合わせ</a></li>
        </ul>
    </nav>
    <h1>NEWS</h1>
    <a href="2021-11-24/">
        <div class="news_article">
            <div class="image">
                <img src="img/IMG_0053.JPG" alt="">
            </div>
            <div class="texts">
                    <p>第9回経済学部プレゼンテーション・コンテストで最優秀賞を受賞しました！</p>
                    <p class="date">2021年11月24日</p>
            </div>
        </div>
    </a>
    </div>
    <footer>
    <p>©︎ 2022 獨協大学 堀江ゼミd-salon制作班</p>
    </footer>
    </body>
<script type="text/javascript" src="../menu/script.js"></script>

</html>