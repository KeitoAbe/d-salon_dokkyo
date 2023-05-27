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
    <title>d-salonとは</title>
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
            <li><a href="#">d-salonとは</a></li>
            <li><a href="../news/">NEWS</a></li>
            <li><a href="../contact/">お問い合わせ</a></li>
        </ul>
    </nav>
    <div class="opinionForm">
        <div class="attention">
            <p>d-salonは大学公認アプリではありません。</p>
            <p>学籍番号の利用には情報漏洩が無いよう十分注意して取り扱っておりますが、今後大学からの指示により、変更等が入る可能性があります。</p>
        </div>
        <label for="opinion">
            <div class="about_d-salon">
                <h1>d-salonとは</h1>
            </label>
            <p>d-salonとは、堀江郁美ゼミのd-salon制作班が制作している獨協生が気軽に話すことができるコミュニケーションサイトです。</p>
            <img src="slide/Slide3.jpg" alt="">
            <p>友達を作る場所がない、気軽に相談できる場がない、どのようにして履修を組めば良いのかが分からない。</p><br>
            <p>これらは、4月に聞いたコロナ禍のオンライン授業で1年間学校に通うことのできなかった現2年生の声です。</p><br>
            <p>目まぐるしく変わる感染状況に日々翻弄され、いつまたオンライン授業が主流になるか分からない、先が不透明な中で、学生のこうした不安を取り除くことができればと思い今回の制作をすることに決めました。</p><br>
            <p>実際に他の学生が、コロナ禍でのオンライン授業をどのように思っているのかを知るために、10月2日から10月20日の計18日間、Google formにおいて、102名の学生にアンケート調査を行いました。</p>
            <img src="slide/Slide5.jpg" alt="">
            <p>こちらが、学生が挙げた不安要素です。</p>
            <p>課題がきちんと提出出来ているか、通信環境で学習が妨げられてしまうなどといった声がありました。</p><br>
            <p>交友関係に関して多くの学生が不安を抱いていることが分かったので、対面授業と比較してどれくらいコミュニケーションが取りづらくなったのか調査しました。</p>
            <img src="slide/Slide6.jpg" alt="">
            <p>教員とのコミュニケーションをやや取りにくい、取りにくいと回答した学生は、全体の約55％。学生間のコミュニケーションを同様に答えた学生は、全体の約81％でした。</p><br>
            <p>このことから、オンライン授業は学生に取って、コミュニケーションを取りづらい環境にあると言えます。</p>
            <img src="slide/Slide7.jpg" alt="">
            <p>続いて、多くの学生にとってd-salonを使いやすい、身近なものにするために、普段どのようにして情報を入手しているかの調査も行いました。</p><br>
            <p>一番回答数が多かったのがホームページ、次いでSNSという結果が得られました。</p><br>
            <p>この結果を元に、堀江ゼミの学生に「獨協生のみが使用できるwebサイト・SNSがあったら使用したいか」と聞いたところ、ほとんどの学生が是非使ってみたいという回答をして頂くことが出来ました。</p>
            <img src="slide/Slide10.jpg" alt="">
            <p>以上、アンケート調査の結果から、獨協生限定のコミュニティツールを作成すれば、学習環境が変化したとしても対面授業と同様の実りある学生生活を送るサポートが出来ると考えました。</p>
            <img src="slide/Slide12.jpg" alt="">
            <p>それでは、d-salonの8つの注目ポイントについてご紹介します。</p><br>
                <p><b>質問掲示板</b></p>
                <p>疑問や悩み事について学生同士で質問や回答をすることができます。</p><br>
                <p><b>みんなの履修</b></p>
                <p>おすすめの授業について学生同士で紹介し合うことができます。</p><br>
                <p><b>授業チャット</b></p>
                <p>時間割を登録すると同じ授業を受けている人同士でグループチャットをすることができます。</p><br>
                <p><b>オープンチャット</b></p>
                <p>同じ興味・関心を持つ学生同士で情報交換や交流を楽しむことができます。</p><br>
                <p><b>マップ</b></p>
                <p>学内の施設についての詳細や大学周辺の施設について知ることができます。</p><br>
                <p><b>ゼミ・部活動紹介</b></p>
                <p>新しい出会いを探す際に活用していただければと思います。</p><br>
                <p><b>チャットボット</b></p>
                <p>大学についての質問を入力すると、d-salonのキャラクター、どく子ちゃんが答えてくれます。</p><br>
                <p><b>ログイン機能</b></p>
                <p>学籍番号とパスワードを使用します。</p><br>
            <p>以上8つの機能をd-salonに搭載することによって、私たちはオンライン授業でも対面授業と同等の実りある学生生活をサポートすることが出来ると考えます。</p>
        </div>
            <div class="about_dokuko">
                <label for="opinion">
                    <h1>どく子ちゃんについて</h1>
                </label>
                <img src="../img/S__69672988.jpg" alt="" class="dokuko_img">
                <p>どく子ちゃんはd-salonオリジナルの大学非公認キャラクターです。獨協大学のイメージキャラクターどく太くんの妹として、d-salon制作班のメンバーによって生み出されました。</p><br>
                <p>大きさはリンゴ3つ分で、好きな食べ物は学食のトリコ丼、趣味は自慢の羽根で大学内を飛び回ることです。</p>
            </div>
            </div>
        </div>
            <footer>
                <p>©︎ 2022 獨協大学 堀江ゼミd-salon制作班</p>
            </footer>
        </body>
<script type="text/javascript" src="../menu/script.js"></script>

</html>