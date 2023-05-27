<?php

session_start();

// データベースの接続情報
require("../board/database.php");
$_SESSION['return'] = $_SERVER["REQUEST_URI"];

//現在のページを取得 存在しない場合は1とする
$page = 1;
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
  $page = (int)$_GET['page'];
}
if (!$page) {
  $page = 1;
}

//ページ毎の件数を設定
$row_count = 50;

if (isset($_GET['topic'])) {
  $topic = htmlspecialchars($_GET['topic'], ENT_QUOTES);
}

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (isset($_GET['input'])) {
  $input = $_GET['input'];
} else {
  $input = "";
}

if ($mysqli->connect_errno) {
  $error_message[] = 'データの読み込みに失敗しました。 エラー番号 ' . $mysqli->connect_errno . ' : ' . $mysqli->connect_error;
} else {
  $mysqli->set_charset('utf8');
  $input = $mysqli->real_escape_string($input);
  $page = $mysqli->real_escape_string($page);
  $row_count = $mysqli->real_escape_string($row_count);
  // ここにデータを取得する処理が入る
  $sql = "SELECT * FROM `class` WHERE `CourseName` LIKE '%$input%' or `Instructor` LIKE '%$input%' or `CourseCode` = '$input' LIMIT " . (($page - 1) * $row_count) . ", " . $row_count;

  $res = $mysqli->query($sql);

  if ($res) {
    //投稿の取得
    $message_array = $res->fetch_all(MYSQLI_ASSOC);
  }

  $mysqli->close();
  // 変数の初期化
  $mysqli = null;
  $sql = null;
  $res = null;
  $post_count = null;

  // データベースに接続
  $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  $mysqli->set_charset('utf8');
  $input = $mysqli->real_escape_string($input);
  // ここにデータを取得する処理が入る
  $sql = "SELECT count(no) FROM `class` WHERE `CourseName` LIKE '%$input%' or `Instructor` LIKE '%$input%' or `CourseCode` = '$input'";
  $res = $mysqli->query($sql);

  if ($res) {
    $post_count = $res->fetch_assoc();
  }

  $mysqli->close();
}

include_once("../board/Paging.php");

//オブジェクトを生成
$pageing = new Paging();
//1ページ毎の表示数を設定
$pageing->count = $row_count;
//全体の件数を設定しhtmlを生成
$pageing->setHtml($post_count['count(no)']);
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../board/css/paging.css">

  <link rel="stylesheet" href="css/search.css">
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <title>d-salon</title>
</head>

<body>
  <div class="t_container">
    <header class="toroku">
      <a href="index.php" class="back"><i class="fa fa-arrow-left"></i></a>
      <p class="do_tweet">授業登録</p>
    </header>
    <div class="container">
      <div class="class_list">
        <div class="search">
          <form action="" class="search_container">
            <input type="text" id="input" name="input" placeholder="開講科目名・教員名・時間割コード" value="<?php if (isset($_GET['input'])) {
                                                                                                echo htmlspecialchars($_GET['input'], ENT_QUOTES);
                                                                                              }; ?>">
            <input type="submit" value="&#xf002">
          </form>
        </div>
        <table class="table table-bordered" id="class">
          <?php foreach ($message_array as $value) { ?>
            <div class="class" onclick="no(<?php echo $value['No'] ?>)">
              <?php echo $value['Semester'] . "　" . $value['Day'] . "　" . $value['CourseAffiliation'] . "<br>" . $value['CourseName'] . "<br>" . $value['Instructor']; ?>
            </div>
          <?php } ?>
        </table>
      </div>
      <?php echo $pageing->html; ?>
    </div>
  </div>
</body>
<script src="js/search.js"></script>

</html>