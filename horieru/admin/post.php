<?php
session_start();

// データベースの接続情報
require("../../board/database.php");

// タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');


if (!empty($_POST['btn_submit'])) {

  //画像の保存先
  $path = '../images/';

  // タイムゾーン設定
  date_default_timezone_set('Asia/Tokyo');
  $today = date("YmdHis");

  if (isset($_FILES['item-image'])&&($_FILES['item-image']['error'] === 0)) {
    move_uploaded_file($_FILES['item-image']['tmp_name'], $path . $today . $_FILES['item-image']['name']);
    $file_name = $today . $_FILES['item-image']['name'];
  }

  // データベースに接続
  $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

  if ($mysqli->connect_errno) {
    $error_message = 'データの読み込みに失敗しました。 エラー番号 ' . $mysqli->connect_errno . ' : ' . $mysqli->connect_error;
  } else {

  // 文字コード設定
  $mysqli->set_charset('utf8');

  $title = $_POST['title'];
  $author = $_POST['author'];
  $description = $_POST['description'];
  $category = $_POST['category'];
  $book_condition = $_POST['book_condition'];

  // SQL文の作成
  $stmt = $mysqli->prepare("INSERT INTO `horieru`(`title`, `author`, `description`, `category`, `book_condition`, `item-image`) VALUES ( ?, ?, ?, ?, ?, ? )");

  // パラメータを設定
  $stmt->bind_param('ssssss', $title, $author , $description , $category , $book_condition, $file_name);

  // 実行
  $res = $stmt->execute();
  $stmt->close();

  // データベースの接続を閉じる
  $mysqli->close();
  }

  header('Location: ./');
}


?>

<!doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="">
  <title>商品投稿 | horieru </title>

 <!-- Scripts -->
 <script src="../app.js" defer></script>

 <!-- Fonts -->
 <link rel="dns-prefetch" href="//fonts.gstatic.com">
 <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

 <!-- Styles -->
 <link href="../app.css" rel="stylesheet">
 <link rel="shortcut icon" href="../images/logo.ico">
</head>
<body>

  <div id="app">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
      <div class="container">
        <a class="navbar-brand" href="./">
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
        <div class="font-weight-bold text-center border-bottom pb-3 pt-3" style="font-size: 24px">商品を投稿する</div>
        <form method="POST" action="" class="p-5" enctype="multipart/form-data">
          <input type="hidden" name="_token" value="BkCXx8muhBdvtQYNv5VhRfC6YYzjUgHItpfNjBto">
          <div>商品画像</div>
          <span class="item-image-form image-picker">
            <input type="file" name="item-image" class="d-none" accept="image/png,image/jpeg,image/gif" id="item-image" />
            <label for="item-image" class="d-inline-block" role="button">
              <img src="../Photo.png" style="object-fit: cover; width: 150px; height: 150px;">
            </label>
          </span>

          <div class="form-group mt-3">
            <label for="name">タイトル</label>
            <input id="name" type="text" class="form-control " name="title" value="" required autocomplete="title" autofocus>
          </div>

          <div class="form-group mt-3">
            <label for="name">著者名</label>
            <input id="name" type="text" class="form-control " name="author" value="" required autocomplete="author" autofocus>
          </div>
          <div class="form-group mt-3">
            <label for="description">本の詳細</label>
            <textarea id="description" class="form-control " name="description" required autocomplete="description" autofocus></textarea>
          </div>
          <div class="form-group mt-3">
            <label for="category">カテゴリ</label>
            <select name="category" class="custom-select form-control ">
              <optgroup label="">全て</option>
                <option value="経済系" class="font-weight-bold">経済系</option>
                <option value="経済系 / 経済" >　経済</option>
                <option value="経済系 / 経営" >　経営</option>
                <option value="経済系 / 国際" >　国際</option>
                <option value="経済系 / 数学" >　数学</option>
                <option value="経済系 / 環境" >　環境</option>
                <option value="語学系" class="font-weight-bold" >語学系</option>
                <option value="語学系 / 英語" >　英語</option>
                <option value="語学系 / ドイツ語" >　ドイツ語</option>
                <option value="語学系 / フランス語" >　フランス語</option>
                <option value="法律系" class="font-weight-bold" >法律系</option>
                <option value="法律系 / 法律" >　法律</option>
                <option value="法律系 / 行政" >　行政</option>
                <option value="その他" class="font-weight-bold" >その他</option>
                <option value="その他 / その他" >　その他</option>
              </select>
            </div>

            <div class="form-group mt-3">
              <label for="book_condition">本の状態</label>
              <select name="book_condition" class="custom-select form-control ">
                <option value="新品・未使用" >新品・未使用</option>
                <option value="未使用に近い" >未使用に近い</option>
                <option value="目立った傷や汚れなし" >目立った傷や汚れなし</option>
                <option value="書き込みあり・やや汚れあり" >書き込みあり・やや汚れあり</option>
                <option value="全体的に書き込みがある" >全体的に書き込みがある</option></select></div>

                <div class="form-group mb-0 mt-3">
                  <input class="submit" type="submit" name="btn_submit" value="投稿する">
                </div>

              </form>
            </div>
          </div>
        </div>
      </main>
    </div>
  </body>
  </html>
