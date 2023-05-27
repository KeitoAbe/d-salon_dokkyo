<?php

session_start();

// データベースの接続情報
require("board/database.php");

//ログイン
require("login_check.php");
$_SESSION['return'] = $_SERVER["REQUEST_URI"];

?>
<!doctype html>
<html>
    
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta property="og:url" content="https://d-salon.jp/" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content=" d-salon" />
    <meta property="og:description" content="d-salon 獨協大学生限定コミュニティーサイト" />
    <meta property="og:site_name" content="d-salon 獨協大学" />
    <meta property="og:image" content="https://d-salon.jp/board/img/logo.png" />
    <meta name="description" content="d-salonとは、獨協大学の学生だけが利用できるオンラインでの情報交換の場です。">
    <meta name="thumbnail" content="img/S__69672988.jpg" />
    <link rel="icon" type="image/png" href="https://d-salon.jp/board/img/icon.png">
    <link rel="apple-touch-icon" sizes="150x150" href="https://d-salon.jp/board/img/app_icon.png">
    <link rel="stylesheet" href="css/top.css?ver=1.13">
    <link rel="stylesheet" href="menu/style.css" type="text/css" />
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <title>d-salon 獨協大学生限定コミュニティーサイト</title>
</head>

<body>
    <div class="overlay"></div>
    <div class="container">
        <image src="img/logo.png" class="logo">
            <a class="login" onclick="profile(<?php echo $user_number; ?>)"><img class="home-icon btn_open" src="board/icon/<?php echo $user_number; ?>" /></a>
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <nav class="globalMenuSp">
                <ul>
                    <li><a href="#">ホーム</a></li>
                    <li><a href="about/">d-salonとは</a></li>
                    <li><a href="news/">NEWS</a></li>
                    <li><a href="contact/">お問い合わせ</a></li>
                    <?php if ($user_number !== 0) { ?>
                        <li><a onclick="logout();">ログアウト</a></li>
                    <?php } ?>
                </ul>
            </nav> 
            <div class="all">
                <div class="circle"><a href="board/"><img src="img/question_bbs.png" class="icon" alt="質問掲示板"></a></div>
                <div class="circle"><a href="assessment/"><img src="img/everybodys_course.png" class="icon" alt="みんなの履修"></div>
                <div class="circle"><a href="chat/"><img src="img/class_chat.png" class="icon" alt="授業チャット"></div>
                <div class="circle"><a href="DokkyoQuiz_app/"><img src="img/dokkyoquiz.png" class="icon" alt="獨協クイズ"></div>
                <div class="circle"><a href="map/map2.html"><img src="img/map.png" class="icon" alt="マップ"></div>
                <div class="circle"><a href="activity/"><img src="img/introduction.png" class="icon" alt="ゼミ・部活動紹介"></div>
                <div class="circle"><a href="gakusei/"><img src="img/gakusei2.png" class="icon" alt="学生団体"></div>
                <div class="circle"><a href="gurume/map-food2.html"><img src="img/gurume.png" class="icon" alt="獨協グルメ"></div>
                <div class="circle"><a href="horieru/"><img src="img/horieru2.png" class="icon" alt="horieru"></div>


            </div>
            <a href="chatbot/">
                <image src="img/dokuko.png" class="dokuta"></image>
            </a>
            <div class="link_container balloon1">
                <ul>
                    <li><a href="https://www.dokkyo.ac.jp/">獨協ホームページ</a></li>
                    <li><a href="https://dreams.dokkyo.ac.jp/campusweb/campusportal.do" data-sp-href="https://dreams.dokkyo.ac.jp/campusweb/campussmart.do?locale=ja_JP">PorTaⅡ</a></li>
                    <li><a href="https://bb.dokkyo.ac.jp/webapps/portal/execute/tabs/tabAction?tab_tab_group_id=_12_1">My DOC</a></li>
                    <li><a href="https://manaba.dokkyo.ac.jp/" data-sp-href="https://manaba.dokkyo.ac.jp/s/home">manaba</a></li>
                    <li><a href="https://webmail.dokkyo.ac.jp/cgi-bin/index.cgi">Webメール</a></li>
                </ul>
            </div>
            <a class="campus_system">学内システムはこちら</a>
    </div>
    <script>
        // manifestのlinkタグを生成
        function setManifest(path) {
            const manifest = document.createElement('link');
            manifest.rel = 'manifest';
            manifest.href = path;
            document.head.appendChild(manifest);
        }
            setManifest('manifest.json');
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('sw.js').then(function(registration) {
            });
        }
    </script>
</body>
<script type="text/javascript" src="menu/script.js"></script>
<script type="text/javascript" src="js/top.js"></script>
<script>
    swal({
  title: "お知らせ",
  text: `d-salonは4月末をもちましてサービス終了予定です。
  ご利用頂きありがとうございました。`,
  button: "OK",
});
</script>
</html>