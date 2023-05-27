<?php
session_start();
if (!empty($_SESSION['error_message'])) {
  $error_message = $_SESSION['error_message'];
  unset($_SESSION['error_message']);
}

?>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <title>d-salon</title>
  <link rel='stylesheet' href='board/css/style.css' type='text/css' media='all' />
  <link rel='stylesheet' href='board/css/index.css' type='text/css' media='all' />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scale=0">
  <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <link rel="icon" type="image/png" href="img/icon.png">
  <link rel="apple-touch-icon" sizes="180x180" href="img/app_icon.png">
</head>

<body onLoad="document.form1.user_id.focus();">
  <div class="d-container">
    <div class="t_container">
      <header>
        <a href="javascript:history.back()" class="back"><i class="fa fa-arrow-left"></i></a>
        <p class="home">アカウントを作成</p>
      </header>
      <div class="twitter__container" id="container">
        <div class="container">
          <img class="logo" src="img/logo.png">
          <?php if (!empty($error_message)) : ?>
            <div class="error">
              <?php echo $error_message; ?>
            </div>
          <?php endif; ?>
          <p>獨協大学の学籍番号を入力してください</p>
          <form action="dokkyo_mail_confirm.php" name="form1" method="POST">
            <input type="text" name="user_id" placeholder="学籍番号">
            <input type="submit" name="login" value="次へ">
          </form>
          <br>
          <p>【学籍番号の取り扱いについて】</p>
          <p>学籍番号の利用には情報漏洩が無いよう十分注意して取り扱っております。</p>
          <p>獨協大学生であることを認証する目的のみで使用し、個人が特定されることはありません。</p>
        </div>
      </div>
    </div>
  </div>
</body>
<script src="js/check_gakuseki.js"></script>

</html>