//返答を取得
var bot_dictionary;
$.getJSON("https://script.google.com/macros/s/AKfycbyFUSiOd75JyMGfUlkRqHmtBGuIq5YMaXE5lmllIquPt6REsGwO3ua3EuBvqRN77RMkKw/exec", function(data) {
    bot_dictionary = data;
});

//チャットボット返信
function write_message() {
    var message = document.getElementById("message").value;
    if (message != '') {
        document.getElementById("message").value = '';
        $('.chat_container').append(`
      <div class='chat_right'>
      <p class='post_date_right'></p>
      <p class='right_balloon'>${message.replace(/\n|\r\n|\r/g, '<br>')}</p>
      <br class='clear_balloon'/>
      </div>`);
        var element = document.documentElement;
        var bottom = element.scrollHeight - element.clientHeight;
        window.scroll(0, bottom);
        if (bot_dictionary.some((u) => ~message.indexOf(u.key1) && ~message.indexOf(u.key2))) {
            var dokuta_answer = (bot_dictionary.find((u) => ~message.indexOf(u.key1) && ~message.indexOf(u.key2)));
            setTimeout(function() {
                $('.chat_container').append(`
                <p class='name'>どく子ちゃん</p>
                <div class='chat_left'>
                <img class='icon_img' src='../board/img/dokuta.png'>
                <p class='left_balloon'>${dokuta_answer['ans']}</p>
                <br class='clear_balloon'/>
                </div>`);
                question_send(message, true);
                scrollbottom();
            }, 1000);
        } else {
            //回答未登録のキーワードのとき
            setTimeout(function() {
                $('.chat_container').append(`
                <p class='name'>どく子ちゃん</p>
                <div class='chat_left'>
                <img class='icon_img' src='../board/img/dokuta.png'>
                <p class='left_balloon'>うーん、難しい…</p>
                <br class='clear_balloon'/>
                </div>`);
                $('.chat_container').append(`
                <p class='name'>どく子ちゃん</p>
                <div class='chat_left'>
                <img class='icon_img' src='../board/img/dokuta.png'>
                <p class='left_balloon'>履修登録、PorTaⅡ、図書館などについて聞いてほしい！</p>
                <br class='clear_balloon'/>
                </div>`);
                question_send(message, false);
                scrollbottom();
            }, 1000);
        }
    }
}

//下に自動スクロール
function scrollbottom() {
    var element = document.documentElement;
    var bottom = element.scrollHeight - element.clientHeight;
    window.scroll(0, bottom);
}

//質問ログ送信
function question_send(question, reply) {
    $.ajax({
        type: "POST",
        url: "https://script.google.com/macros/s/AKfycbwI5HZsiAr81diMLhDdq9MydWyHRpjQ8IJNzf7UZ6lYD_w1Rd7mxBqFIjLAPBpPlAKMrw/exec",
        data: {
            question: question,
            reply: reply,
        },
        dataType: "json"
    }).done(function(data) {}).fail(function() {}).always(function() {});
}