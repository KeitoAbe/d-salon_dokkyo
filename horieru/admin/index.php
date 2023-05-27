<?php
session_start();

// データベースの接続情報
require("../../board/database.php");

// タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');

if (isset($_GET['id'])) {
  $id = $_GET['id'];

// データベースに接続
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$sql = "DELETE FROM `horieru` WHERE id = $id";
$res = $mysqli->query($sql);
}

// データベースに接続
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// 文字コード設定
$mysqli->set_charset('utf8');

$sql = "SELECT * FROM `horieru`";
$res = $mysqli->query($sql);

if ($res) {
  //投稿の取得
  $item_array = $res->fetch_all(MYSQLI_ASSOC);
}

$mysqli->close();

?>

<!doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="">
  <title>管理ページ | horieru </title>

 <!-- Scripts -->
 <script src="app.js" defer></script>

 <!-- Fonts -->
 <link rel="dns-prefetch" href="//fonts.gstatic.com">
 <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
 <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

 <!-- Styles -->
 <link href="../app.css" rel="stylesheet">
 <link rel="shortcut icon" href="../images/logo.ico">
</head>
<body>

  <div id="app">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
      <div class="container">
        <a class="navbar-brand" href="">
          <img src="../horieru.png" style="height: 60px;" alt="Melpit">
        </a>
        <h2>管理ページ</h2>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">

          <!-- Left Side Of Navbar -->
          <ul class="navbar-nav mr-auto">
          </ul>
      </div>
    </div>
  </nav>

  <main class="py-4">
    <div class="container">
      <div class="row">
      <div class="col-8 offset-2">
      </div>
    </div>

    <div class="row">
      <div class="col-8 offset-2 bg-white">
        <div class="font-weight-bold text-center border-bottom pb-3 pt-3" style="font-size: 24px">商品を管理する</div>
        <?php foreach ($item_array as $value) { ?>
          <div class="management">
            <p><?php echo $value['title'];?></p>
            <p><?php echo $value['author'];?></p>
            <a href="?id=<?php echo $value['id']?>">
              <button class="deleteBtn">削除</button>
            </a>
          </div>
        <?php } ?>
            </div>
          </div>
        </div>

        <a href="post.php"
   class="bg-secondary text-white d-inline-block d-flex justify-content-center align-items-center flex-column"
   role="button"
   style="position: fixed; bottom: 30px; right: 30px; width: 150px; height: 150px; border-radius: 75px;">
   <div style="font-size: 24px;">投稿</div>

   <div>
     <i class="fas fa-camera" style="font-size: 30px;"></i>
   </div>
  </a>
      </main>
    </div>
  </body>
  </html>
