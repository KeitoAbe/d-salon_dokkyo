<?php

session_start();
$_SESSION['return'] = $_SERVER["REQUEST_URI"];

// データベースの接続情報
require("../board/database.php");

//ログイン
require("../login_check.php");

$check_doble = array();

if (isset($_GET['topic'])) {
  $topic = htmlspecialchars($_GET['topic'], ENT_QUOTES);
}

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($mysqli->connect_errno) {
  $error_message[] = 'データの読み込みに失敗しました。 エラー番号 ' . $mysqli->connect_errno . ' : ' . $mysqli->connect_error;
} else {
  $mysqli->set_charset('utf8');
  $user_number = $mysqli->real_escape_string($user_number);
  // ここにデータを取得する処理が入る
  $sql = "SELECT * FROM register_class INNER JOIN class ON register_class.no = class.no left JOIN chat ON class.no = chat.no WHERE register_class.user_number = '$user_number'  order by chat.id DESC; ";
  $res = $mysqli->query($sql);

  //and chat.id = 452
  //id IN (SELECT MAX(id) FROM chat GROUP BY chat.no)
  //INNER JOIN chat ON class.no = chat.no
  //GROUP BY chat.no

  if ($res) {
    //投稿の取得
    $message_array = $res->fetch_all(MYSQLI_ASSOC);
  }
  $mysqli->close();
}

//未読数取得
function notify($no)
{
  $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  $mysqli->set_charset('utf8');
  $no = $mysqli->real_escape_string($no);
  $user_number = $mysqli->real_escape_string($_SESSION['user_number']);
  // ここにデータを取得する処理が入る
  $sql = "SELECT COUNT( * ) FROM `chat_notification` WHERE no = '$no' and user_number = '$user_number'";
  $res = $mysqli->query($sql);
  $already_read = $res->fetch_assoc();
  $read_count = $already_read["COUNT( * )"];

  $sql = "SELECT COUNT( * ) FROM `chat` WHERE no = '$no'";
  $res = $mysqli->query($sql);
  $posted_number = $res->fetch_assoc();
  $posted_count = $posted_number["COUNT( * )"];

  if ($read_count !== $posted_count) {
    return $posted_count - $read_count;
  }
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../board/css/paging.css">
  <link rel="stylesheet" href="css/search.css">
  <link rel='stylesheet' href='../board/css/tweet.css' type='text/css' media='all' />
  <link rel="stylesheet" href="css/index.css?ver=1.1">
  <link rel='stylesheet' href='css/profile_class.css' type='text/css' media='all' />
  <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
  <link rel="stylesheet" href="../menu/style.css" type="text/css" />
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <title>d-salon 授業チャット</title>
</head>

<body>
  <div class="overlay"></div>
  <header>
    <a href="../">
      <img src="../board/img/logo.png" alt="" class="logo">
    </a>
    <a><img onclick="profile(<?php echo $user_number; ?>)" class="home-icon" src="../board/icon/<?php echo $user_number; ?>" /></a>
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
        <li><a href="../contact/">お問い合わせ</a></li>
        <?php if ($user_number !== 0) { ?>
          <li><a onclick="logout();">ログアウト</a></li>
        <?php } ?>
      </ul>
    </nav>
  </header>
  <div class="t_container">
    <div class="overlay-white"></div>
    <div class="container">
      <div class="postQuestion">
        <img src="../board/img/dokuta.png" alt="">
        <p>同じ授業の人と繋がろう！</p>
        <div class="post">
          <a href="search.php">授業登録する</a>
        </div>
      </div>
      <div class="class_list">
        <table class="table table-bordered" id="class">
          <?php foreach ($message_array as $value) { ?>
            <?php if (!in_array($value['no'], $check_doble)) { ?>
              <?php if ($value['no'] !== null) {
                array_push($check_doble, $value['no']);
              } ?>
              <div class="class">
                <div onclick="no(<?php echo $value['No'] ?>)">
                  <?php echo $value['Semester'] . "　" . $value['Day'] . "　" . $value['CourseAffiliation'] . "<br>" . $value['CourseName'] . "<br>" . $value['Instructor']; ?>
                  <?php if (notify($value['no']) !== null) { ?>
                    <div class="notify">
                      <?php echo notify($value['no']); ?>
                    </div>
                  <?php } ?>
                </div>
              </div>
            <?php } ?>
          <?php }?>
        </table>
        <?php if(empty($message_array)){ ?>
          <div class="no_register">
            <p>授業が登録されていません</p>
          </div>
        <?php } ?>
      </div>
    </div>
  </div>
</body>
<script type="text/javascript" src="js/d-salon.js"></script>
<script src="js/index.js"></script>
<script type="text/javascript" src="../menu/script.js"></script>

</html>