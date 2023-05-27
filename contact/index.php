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
    <meta name="robots" content="noindex">
    <link rel="stylesheet" href="../activity/zemi/gaikokugo.css" type="text/css" />
    <link rel="stylesheet" href="style.css" type="text/css" />
    <link rel="stylesheet" href="icomoon/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../menu/style.css" type="text/css" />
    <title>d-salon お問い合わせ</title>
</head>

<body>
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
            <li><a href="../news/">NEWS</a></li>
            <li><a href="../Preparation/getready.html">使い方ガイド</a></li>
            <li><a href="#">お問い合わせ</a></li>
        </ul>
    </nav>
    <div class="opinionForm">
        <label for="opinion">
            <h1>d-salonに関するご意見・ご要望はこちらから</h1>
        </label>
        <form method="post" action="./confirm.php">
            <textarea name="opinion" id="opinion" name="opinion"><?php if (isset($opinion)) {
                                                                        echo $opinion;
                                                                    }; ?></textarea><br>
            <div class="submitBtn">
                <input type="submit" value="送信">
            </div>
        </form>
        <p>制　作：獨協大学 堀江ゼミd-salon制作班</p>
        <p>メール：<a href="mailto:dsalon.dokkyo@gmail.com">dsalon.dokkyo@gmail.com
            </a></p>
    </div>
    <ul class="circle_group clearfix">
        <li class="sns_circle twitter"><span class="icon-twitter"></span><a href="https://twitter.com/dsalon_dokkyo/"></a></li>
        <li class="sns_circle instagram"><span class="icon-instagram"></span><a href="https://www.instagram.com/dsalon_2021/"></a></li>
        <li class="sns_circle line"><span class="icon-line"></span><a href="https://lin.ee/jQjhudg"></a></li>
    </ul>
</body>
<script type="text/javascript" src="../menu/script.js"></script>

</html>