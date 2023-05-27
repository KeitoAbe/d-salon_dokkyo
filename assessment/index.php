<?php

session_start();

//ログイン
require("../login_check.php");
$_SESSION['return'] = $_SERVER["REQUEST_URI"];

// データベースの接続情報
require("../board/database.php");

// タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');
// 変数の初期化
$current_date = null;

$message = array();
$message_array = array();
$success_message = null;
$error_message = array();

$pdo = null;
$stmt = null;
$res = null;
$option = null;

// データベースに接続
try {
    $option = array(
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::MYSQL_ATTR_MULTI_STATEMENTS => false,
	);
$pdo = new PDO('mysql:charset=UTF8;dbname='.DB_NAME.';host='.DB_HOST , DB_USER, DB_PASS, $option);
} catch(PDOException $e) {

    // 接続エラーのときエラー内容を取得する
    $error_message[] = $e->getMessage();
}

if( !empty($_POST['btn_submit']) ) {
    // 空白除去
    $view_name = preg_replace( '/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u', '', $_SESSION['user_name']);
	$gakubu = preg_replace( '/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u', '', $_POST['gakubu']);
	$gakka = preg_replace( '/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u', '', $_POST['gakka']);
	$lessonname = preg_replace( '/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u', '', $_POST['lessonname']);
	$teacher = preg_replace( '/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u', '', $_POST['teacher']);
	$difficulty = preg_replace( '/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u', '', $_POST['difficulty']);
	$satisfaction = preg_replace( '/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u', '', $_POST['satisfaction']);
	$message = preg_replace( '/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u', '', $_POST['message']);
	
    // 表示名の入力チェック
	if( empty($view_name) ) {
		$error_message[] = '表示名を入力してください';
	}else {
		
		// セッションに表示名を保存
		$_SESSION['view_name'] = $view_name;
	}
	
	
	// メッセージの入力チェック
	
	if( empty($gakubu) ) {
		$error_message[] = '学部を選択してください';
	}
	
	if( empty($gakka) ) {
		$error_message[] = '学科を選択してください';
	}
	
	
	if( empty($lessonname) ) {
		$error_message[] = '授業名を入力してください';
	}
	
	if( empty($teacher) ) {
		$error_message[] = '教員名を入力してください';
	}

	
	if( empty($difficulty) ) {
		$error_message[] = '大変さを選択してください';
	}
	
	if( empty($satisfaction) ) {
		$error_message[] = '満足さを選択してください';
	}
	
	if( empty($message) ) {
		$error_message[] = '授業感想を入力してください';
	}
	

	
	if( empty($error_message) ) {
	
	// 書き込み日時を取得
		$current_date = date("Y-m-d H:i:s");
// トランザクション開始
		$pdo->beginTransaction();

		try {

		// SQL作成
		$stmt = $pdo->prepare("INSERT INTO assessment (view_name, gakubu, gakka, lessonname, teacher, difficulty, satisfaction,  message, post_date) VALUES ( :view_name, :gakubu, :gakka, :lessonname, :teacher, :difficulty, :satisfaction,  :message, :current_date)");

		// 値をセット
		$stmt->bindParam( ':view_name', $view_name, PDO::PARAM_STR);
		$stmt->bindParam( ':gakubu', $gakubu, PDO::PARAM_STR);
		$stmt->bindParam( ':gakka', $gakka, PDO::PARAM_STR);
		$stmt->bindParam( ':lessonname', $lessonname, PDO::PARAM_STR);
		$stmt->bindParam( ':teacher', $teacher, PDO::PARAM_STR);
		$stmt->bindParam( ':difficulty', $difficulty, PDO::PARAM_STR);
		$stmt->bindParam( ':satisfaction', $satisfaction, PDO::PARAM_STR);
		$stmt->bindParam( ':message', $message, PDO::PARAM_STR);
		$stmt->bindParam( ':current_date', $current_date, PDO::PARAM_STR);

		// SQLクエリの実行
		$stmt->execute();
		// コミット
			$res = $pdo->commit();

		} catch(Exception $e) {

			// エラーが発生した時はロールバック
			$pdo->rollBack();
		}
		if( $res ) {
			$_SESSION['success_message'] = '投稿しました！';
		} else {
			$error_message[] = '書き込みに失敗しました';
		}
		
		// プリペアドステートメントを削除
		$stmt = null;
		header('Location: ./');
		exit;
	}
}
if( empty($error_message) ) {

	// メッセージのデータを取得する
	$sql = "SELECT * FROM assessment ORDER BY post_date DESC";
	$message_array = $pdo->query($sql);
}

// データベースの接続を閉じる
$pdo = null;


?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css" type="text/css" />
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <title>みんなの履修</title>
</head>
<body>
    
    <header>
        <div class="header-content">
            <div class="header-inner">
                <a href="../">
                    <img class="logo" src="../img/logo.png"></img>
                </a>
                <div id="nav-wrapper" class="nav-wrapper">
                    <div class="hamburger" id="js-hamburger">
                        <span class="hamburger__line hamburger__line--1"></span>
                        <span class="hamburger__line hamburger__line--2"></span>
                        <span class="hamburger__line hamburger__line--3"></span>
                    </div>
                    <nav class="sp-nav">
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
                    <div class="black-bg" id="js-black-bg"></div>
                </div>
            </div>
        </div>
    </header>
    
    
    <main>
        <section class="send">
            <h1>みんなの履修</h1>
            
            <?php if (!isset($_SESSION['user_name'])) { ?>
                <h2>投稿するにはログインしてください</h2>
                <div class="toukou loginBtn">
                <a href='../login.php'>ログイン</a>
                </div>
            <?php } ?>    

              <!-- ここにメッセージの入力フォームを設置 -->
            <?php if( empty($_POST['btn_submit']) && !empty($_SESSION['success_message']) ): ?>
              <p class="success_message"><?php echo htmlspecialchars( $_SESSION['success_message'], ENT_QUOTES, 'UTF-8'); ?></p>
              <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>
        　  <?php if( !empty($error_message) ): ?>
    	        <ul class="error_message">
    		            <?php foreach( $error_message as $value ): ?>
    			    <li>・<?php echo $value; ?></li>
    		            <?php endforeach; ?>
    	    </ul>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['user_name'])) { ?>
            
            <form method="post">
                
                <div class="input-block">
                  <div class="select-block">
                    <label>学部：
                      <select id="category-select-1" name="gakubu">
                        <option value="">選択してください</option>
                      </select>
                    </label>
                  </div>
                  <div class="select-block">
                    <label>学科：
                      <select id="sub-category-select-1" name="gakka">
                        <option value="">選択してください</option>
                      </select>
                    </label>
                  </div>
                </div>
                
                
                <div>
                    <label for="lessonname">授業名:</label>
                    <input type="text" id="lessonname" name="lessonname" class="text">
                </div>
                
                <div>
                    <label for="teacher">教員名:</label>
                    <input type="text" id="teacher" name="teacher" class="text">
                </div>
                
                <div>
                    <label for="difficulty">大変さ：
                        <select id="difficulty" name="difficulty">
                            <option value="">選択してください</option>
                            <option value="大変">大変</option>
                            <option value="普通">普通</option>
                            <option value="簡単">簡単</option>
                      </select>
                    </label>
                </div>
                
                <div>
                    <label for="satisfaction">満足さ：
                        <select id="satisfaction" name="satisfaction">
                            <option value="">選択してください</option>
                            <option value="満足">満足</option>
                            <option value="普通">普通</option>
                            <option value="不満足">不満足</option>
                      </select>
                    </label>
                </div>
                
            	<div>
            	    <label for="message">授業感想:</label>
            		<textarea id="message" name="message"><?php if( !empty($message) ){ echo htmlspecialchars( $message, ENT_QUOTES, 'UTF-8'); } ?></textarea>
            	</div>
            	
            	<div class="toukou">
            	   <input type="submit" name="btn_submit" value="投稿！"> 
            	</div>
            </form>
            <?php  } ?>
            
        </section>
        
       <hr>
        <section class="watch">
         <?php if( !empty($message_array) ){ ?>
         <?php foreach( $message_array as $value ){ ?>
         <article>
          <div class="info">
            <h2 class="view_name"><?php echo htmlspecialchars( $value['view_name'], ENT_QUOTES, 'UTF-8'); ?></h2>
            <time><?php echo date('Y年m月d日 H:i', strtotime($value['post_date'])); ?></time>
          </div>
          <div class="sub_info">
            <div class="lesson_info">
              <p class="gakubu"><?php echo nl2br(htmlspecialchars( $value['gakubu'], ENT_QUOTES, 'UTF-8')); ?></p>
              <p class="gakka"><?php echo nl2br(htmlspecialchars( $value['gakka'], ENT_QUOTES, 'UTF-8')); ?></p>
              <p class="lessonname"><?php echo nl2br(htmlspecialchars( $value['lessonname'], ENT_QUOTES, 'UTF-8')); ?></p>
            </div>
            <div class="other">
              <p class="teacher"><?php echo nl2br(htmlspecialchars( $value['teacher'], ENT_QUOTES, 'UTF-8')); ?></p>
              <p class="difficulty">大変さ：<?php echo nl2br(htmlspecialchars( $value['difficulty'], ENT_QUOTES, 'UTF-8')); ?></p>
              <p class="satisfaction">満足さ：<?php echo nl2br(htmlspecialchars( $value['satisfaction'], ENT_QUOTES, 'UTF-8')); ?></p>
            </div>
          </div>
          <p class="message"><?php echo nl2br(htmlspecialchars( $value['message'], ENT_QUOTES, 'UTF-8')); ?></p>
         </article>
         <?php } ?>
         <?php } ?>
        </section>


　　</main> 
　　　 <!--↑mainはbody-->
　　　 
    
    
    <footer>
        
    </footer>
    
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="menu.js"></script>
    <script src="select.js"></script>
</body>
</html>