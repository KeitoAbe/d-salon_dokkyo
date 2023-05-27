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
    <link rel="stylesheet" href="../../activity/zemi/gengo.css" type="text/css" />
    <link rel="stylesheet" href="style.css" type="text/css" />
    <link rel="stylesheet" href="icomoon/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../../menu/style.css" type="text/css" />
    <title>NEWS</title>
</head>

<body>
    <div class="wrapper">

        <a href="../../"><img src="../../img/logo.png" class="logo"></img></a>
        <div class="overlay"></div>
    <div class="hamburger">
        <span></span>
        <span></span>
        <span></span>
    </div>
    <nav class="globalMenuSp">
        <ul>
            <li><a href="../../">ホーム</a></li>
            <li><a href="../../about/">d-salonとは</a></li>
            <li><a href="../">NEWS</a></li>
            <li><a href="../../contact/">お問い合わせ</a></li>
        </ul>
    </nav>
    <h1>第9回経済学部プレゼンテーション・コンテストで最優秀賞を受賞しました！</h1>
    <p>2021年11月24日</p>
    <img src="../img/IMG_0053.JPG" alt="">
    <div class="article_text">
    <p>　2021年11月24日に開催された第9回経済学部プレゼンテーション・コンテストで私たちd-salon制作班が「d-salon制作報告」のプレゼンテーションを行い、最優秀賞を受賞しました。</p><br>
    <strong>
        <a href="https://www.dokkyo.ac.jp/information/2021/20211130004983.html">第9回経済学部プレゼンテーション・コンテスト開催報告 ～大教室で8グループが熱戦～｜獨協大学</a>
    </strong>
    <br><br>
    <p><b>班長・柴崎よりコメント</b></p><br>
        <p>　この度、第9回プレゼンテーション・コンテストで最優秀賞という大変素晴らしい賞を頂き光栄に思います。制作に協力して頂いた堀江先生を初めとする先生方、ゼミ生、このような素晴らしい場を設けて頂いた山崎先生を初めとする実行委員の先生方にこの場をお借りして厚く御礼申し上げます。</p><br>
        <p>　制作は決して順風満帆にはいきませんでした。度重なる話し合い、1万を超えるコーディング、500時間を超える制作時間に、心が折れそうになる時、手が止まってしまう時も多々ありました。それでも「獨協生にとって便利になるようなものを作りたい」この一心で約5ヶ月、全力で戦ってきたメンバーと共に助け合い、ここまで形にすることが出来ました。今後は、多くの学生にd-salonを認知してもらうこと、利用してもらうことが第一の目標です。利用者の声を元に、更なる機能の実装・改善を行い、獨協生に寄り添っていけるそんなWebサイトの制作を目指します。</p>
    <img src="img/IMG_0093.JPG" alt="">
    </div>
    </div>
    <footer>
    <p>©︎ 2022 獨協大学 堀江ゼミd-salon制作班</p>
</footer>
    </body>
<script type="text/javascript" src="../../menu/script.js"></script>

</html>