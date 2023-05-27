<?php
session_start();

$id = $_GET['id'];

// データベースの接続情報
require("../board/database.php");

// タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');

if (isset($_GET['keyword'])) {
  $keyword = $_GET['keyword'];
} else {
  $keyword = "";
}

if (isset($_GET['category'])) {
  $category = $_GET['category'];
} else {
  $category = "";
}

// データベースに接続
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// 文字コード設定
$mysqli->set_charset('utf8');

$sql = "SELECT * FROM `horieru` where `id` = $id";
$res = $mysqli->query($sql);

if ($res) {
  //投稿の取得
  $item_array = $res->fetch_assoc();
}

$mysqli->close();
?>

<!doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
 <meta name="csrf-token" content="v2jniX5beBZIWfs6b4PaW2yRNogVhkhJDyhHSiSs">
 <title>horieru</title>

 <!-- Scripts -->
 <script src="app.js" defer></script>

 <!-- Fonts -->
 <link rel="dns-prefetch" href="//fonts.gstatic.com">
 <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

 <!-- Styles -->
 <link href="app.css" rel="stylesheet">
 <link rel="shortcut icon" href="/images/logo.ico">
 </head>
 <body>

   <div id="app">
     <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
       <div class="container">
         <a class="navbar-brand" href="index.php">
           <img src="horieru.png" style="height: 60px;" alt="horieru">
         </a>
         <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
           <span class="navbar-toggler-icon"></span>
         </button>
         <div class="collapse navbar-collapse" id="navbarSupportedContent">

           <!-- Left Side Of Navbar -->
           <ul class="navbar-nav mr-auto">
           </ul>

           <!-- Right Side Of Navbar -->
           <ul class="navbar-nav ml-auto">
             <form class="form-inline" method="GET" action="index.php">
               <div class="input-group">
                 <div class="input-group-prepend">
                 <select class="custom-select" name="category">
                     <option value="" <?php if ($category == '') { echo 'selected';};?>>全て</option>
                      <option value="経済系" class="font-weight-bold" <?php if ($category == '経済系') { echo 'selected';};?>>経済系</option>
                        <option value="経済系 / 経済" <?php if ($category == '経済系 / 経済') { echo 'selected';};?>>　経済</option>
                        <option value="経済系 / 経営" <?php if ($category == '経済系 / 経営') { echo 'selected';};?>>　経営</option>
                        <option value="経済系 / 国際" <?php if ($category == '経済系 / 国際') { echo 'selected';};?>>　国際</option>
                        <option value="経済系 / 数学" <?php if ($category == '経済系 / 数学') { echo 'selected';};?>>　数学</option>
                        <option value="経済系 / 環境" <?php if ($category == '経済系 / 環境') { echo 'selected';};?>>　環境</option>
                        <option value="語学系" class="font-weight-bold" <?php if ($category == '語学系') { echo 'selected';};?>>語学系</option>
                        <option value="語学系 / 英語" <?php if ($category == '語学系 / 英語') { echo 'selected';};?>>　英語</option>
                        <option value="語学系 / ドイツ語" <?php if ($category == '語学系 / ドイツ語') { echo 'selected';};?>>　ドイツ語</option>
                        <option value="語学系 / フランス語" <?php if ($category == '語学系 / フランス語') { echo 'selected';};?>>　フランス語</option>
                        <option value="法律系" class="font-weight-bold" <?php if ($category == '法律系') { echo 'selected';};?>>法律系</option>
                        <option value="法律系 / 法律" <?php if ($category == '法律系 / 法律') { echo 'selected';};?>>　法律</option>
                        <option value="法律系 / 行政" <?php if ($category == '法律系 / 行政') { echo 'selected';};?>>　行政</option>
                        <option value="その他" class="font-weight-bold" <?php if ($category == 'その他') { echo 'selected';};?>>その他</option>
                        <option value="その他 / その他" <?php if ($category == 'その他 / その他') { echo 'selected';};?>>　その他</option>
                   </select>
                 </div>
                 <input type="text" name="keyword" class="form-control" value="" aria-label="Text input with dropdown button" placeholder="キーワード検索">
                 <div class="input-group-append">
                   <button type="submit" class="btn btn-outline-dark">
                     <i class="fas fa-search"></i>
                   </button>
                 </div>
               </div>
             </form>
           </ul>
         </div>
       </div>
     </nav>

     <main class="py-4">
       <div class="container">
         <div class="row">
          <div class="col-8 offset-2 bg-white">

            <div class="font-weight-bold text-center pb-3 pt-3" style="font-size: 24px"><?php echo $item_array['title']?></div>
            <div class="row">
              <div class="col-4 offset-1">
                <img class="card-img-top" src="<?php if($item_array['item-image'] === NULL) { echo "本イラスト.png";} else { echo 'images/'.$item_array['item-image'];} ;?>">
              </div>
              <div class="col-6">
                <table class="table table-bordered">
                  <tr>
                    <th>著者名</th>
                    <td><?php echo $item_array['author']?></td>
                  </tr>
                  <tr>
                    <th>本の詳細</th>
                    <td><?php echo $item_array['description']?></td>
                  </tr>
                  <tr>
                    <th>カテゴリー</th>
                    <td><?php echo $item_array['category']?></td>
                  </tr>
                  <tr>
                    <th>本の状態</th>
                    <td><?php echo $item_array['book_condition']?></td>
                  </tr>
                </table>
                <p>受け取りを希望する方は、d-salon公式LINEよりお問い合わせください</p>
                <a href="https://lin.ee/jQjhudg">
                  <img src="wide-default.png" alt="" class="line">
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
  </main>
 </div>
</body>
</html>
