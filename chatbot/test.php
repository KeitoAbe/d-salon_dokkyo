<?php
if (count($_POST)) {
    $url = 'https://script.google.com/macros/s/AKfycbwI5HZsiAr81diMLhDdq9MydWyHRpjQ8IJNzf7UZ6lYD_w1Rd7mxBqFIjLAPBpPlAKMrw/exec';
    $data = array(
        'question' => $_POST['question'],
        'reply' => $_POST['reply'],
    );
    $context = array(
        'http' => array(
            'method'  => 'POST',
            'header'  => implode("\r\n", array('Content-Type: application/x-www-form-urlencoded',)),
            'content' => http_build_query($data)
        )
    );
    $response_json = file_get_contents($url, false, stream_context_create($context));
    $response_data = json_decode($response_json);
    var_dump($response_data);
}
?>
<form action="" method="post">
    <input type="text" name="question" placeholder="質問">
    <input type="text" name="reply" placeholder="返答">
    <button type="submit">送信</button>
</form>