<?php
session_start();

$_SESSION['return'] = $_SERVER["REQUEST_URI"];

$toke_byte = openssl_random_pseudo_bytes(16);
$chat_token = bin2hex($toke_byte);

$_SESSION['chat_token'] = $chat_token;

?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <title>d-salon オープンチャット</title>
  <script src="https://kit.fontawesome.com/76b7c7aef8.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <link rel="stylesheet" href="js/dist/css/lightbox.css">
  <script src="js/dist/js/lightbox.js" type="text/javascript"></script>
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <div class="overlay"></div>
  <div class="maxWidth">
    <header>
      <a href="../" class="back"><i class="fa fa-arrow-left"></i></a>
      <p class="do_tweet">オープンチャット</p>
    </header>
    <div class="container">
      <div class='reply_btn'>
        <svg class="reply_icon" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 288.926 288.926" style="enable-background:new 0 0 288.926 288.926;" xml:space="preserve">
          <path d="M235.656,133.414c-24.775-24.776-55.897-41.771-89.999-49.146c-21.59-4.67-43.696-5.347-65.303-2.149v-55.21L0,107.07 l80.354,80.546v-75.08c48.282-8.498,98.84,6.842,134.089,42.091c28.685,28.685,44.482,66.824,44.482,107.391h30 C288.926,213.437,270.008,167.765,235.656,133.414z" />
        </svg>
        <p>リプライ</p>
      </div>
      <div class='delete_btn'><svg class="delete_icon" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org 1999/xlink" x="0px" y="0px" viewBox="0 0 371.117 371.117" style="enable-background:new 0 0 371.117 371.117;" xml:space="preserve">
          <path d="M316.33,64.556c-34.981-27.607-81.424-42.81-130.771-42.81c-49.348,0-95.79,15.204-130.771,42.81 C19.457,92.438,0,129.614,0,169.238c0,23.835,7.308,47.508,21.133,68.46c12.759,19.334,31.07,36.42,53.088,49.564 c-1.016,7.116-6.487,27.941-35.888,52.759c-1.513,1.278-2.13,3.328-1.572,5.229c0.558,1.9,2.185,3.292,4.147,3.55 c0.179,0.023,4.455,0.572,12.053,0.572c21.664,0,65.939-4.302,120.063-32.973c4.177,0.221,8.387,0.333,12.534,0.333 c49.348,0,95.79-15.204,130.771-42.811c35.33-27.882,54.787-65.059,54.787-104.683C371.117,129.614,351.66,92.438,316.33,64.556z M253.515,217.07l-18.842,18.842c-0.976,0.976-2.256,1.464-3.535,1.464c-1.28,0-2.56-0.488-3.535-1.464l-42.044-42.044 l-42.044,42.044c-1.951,1.952-5.119,1.952-7.07,0l-18.842-18.842c-1.953-1.953-1.953-5.119,0-7.071l42.043-42.044l-42.041-42.041 c-1.953-1.953-1.953-5.119,0-7.071l18.842-18.842c1.951-1.952,5.119-1.952,7.07,0l42.042,42.041l42.042-42.041 c1.951-1.952,5.119-1.952,7.07,0l18.842,18.842c1.954,1.953,1.954,5.119,0,7.071l-42.041,42.041l42.043,42.044 C255.468,211.951,255.468,215.117,253.515,217.07z" />
        </svg>
        <p>送信取消</p>
      </div>
      <section>
        <div class="chat_container" id="chat_container">
        </div>
    </div>
    </section>
    <div class="reply">
      <img src="" alt="" id="reply_icon">
      <div class="reply_to">
        <p class="reply_name"></p>
        <p class="reply_message"></p>
      </div>
      <svg class="close_icon" focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
        <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"></path>
      </svg>
    </div>
    <form method="post" name="form1" id="send-form" onsubmit="return false;" action="" enctype="multipart/form-data">
      <div class="form">
        <label class="upload-label">
          <i class="fa fa-image"></i>
          <input name="file1" id="myImage" type="file" accept="image/*" />
        </label>
        <div class="preview">
          <img id="preview">
        </div>
        <svg class="preview_close_icon" focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
          <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"></path>
        </svg>
        <textarea id="message" name="message" class="message" placeholder=""></textarea>
        <label class="submit">
          <svg id="send" class="send_icon" onclick="write_message();" width="30px" height="30px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 100 125" enable-background="new 0 0 100 100" xml:space="preserve">
            <path fill="#94847b" d="M5.1,90.8l7.6-32.9c0.3-1.4,1.5-2.5,3-2.7l42.5-4.4c1.2-0.1,1.2-1.9,0-2.1l-42.5-4.1c-1.5-0.1-2.7-1.2-3-2.6  L5.1,9.2C4.4,6.4,7.4,4.1,10,5.4l83.1,41.6c2.5,1.3,2.5,4.9,0,6.2L10,94.6C7.4,95.9,4.4,93.6,5.1,90.8z" /><text x="0" y="115" fill="#94847b" font-size="5px" font-weight="bold" font-family="'Helvetica Neue', Helvetica, Arial-Unicode, Arial, Sans-serif"></text><text x="0" y="120" fill="#94847b" font-size="5px" font-weight="bold" font-family="'Helvetica Neue', Helvetica, Arial-Unicode, Arial, Sans-serif"></text>
          </svg>
          <input type="submit" name="btn_submit" value="">
        </label>
      </div>
    </form>
  </div>
  </div>
</body>
<script>
  var chat_token = '<?php echo $chat_token ?>';
</script>
<script src="js/chat.js"></script>

</html>