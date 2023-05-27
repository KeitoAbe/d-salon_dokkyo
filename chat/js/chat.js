var last;
var checknew;
var courseName;
var instructor;
var is_image = false;
var is_reply = false;
var reply_user;
var reply_to;
var reply_to_message;
var reply_id;
var delete_id;
var is_reload = false;

//ページを読み込んだとき
window.onload = function() {
        class_name();
        login_user();
        //5秒おきに新着メッセージ確認
        setInterval('readMessage()', 5000);
    }
    //メッセージの読み込み
function readMessage(is_reload) {
    var post_date = new Array();
    var no = (new URL(document.location)).searchParams.get('no');
    $.ajax({
        type: "POST",
        url: "load_message.php",
        data: {
            no: no,
            user_number: user_number,
        },
        dataType: "json"
    }).done(function(data) {
        last = (data[data.length - 1]);
        if ((typeof last != 'undefined' && last['id'] != checknew) || is_reload === true) {
            $(".chat_container").empty();
            data.forEach(function(value) {
                //日時表示
                if (!post_date.includes(value.post_date)) {
                    post_date.push(value.post_date);
                    var today = new Date();
                    const days = ["日", "月", "火", "水", "木", "金", "土"];
                    var todaydate = ((today.getMonth() + 1) + "/" + today.getDate() + "(") + days[today.getDay()] + ")";
                    var yesterdaydate = ((today.getMonth() + 1) + "/" + (today.getDate() - 1) + "(") + (days[today.getDay() - 1]) + ")";
                    if (value.post_date == todaydate) {
                        $('.chat_container').append(`<div class='date'><p>今日</p></div>`);
                    } else if (value.post_date == yesterdaydate) {
                        $('.chat_container').append(`<div class='date'><p>昨日</p></div>`);
                    } else {
                        $('.chat_container').append(`<div class='date'><p>${value.post_date}</p></div>`);
                    }
                }
                //メッセージ表示
                //自分のメッセージ
                if (value.user_name == user_name) {
                    if (value.image === null) {
                        if (value.reply_id === null) {
                            $('.chat_container').append(`
                            <div class='chat_right chat' id=${value.id}>
                            <p class='post_date_right'>${value.post_time}</p>
                            <div class='right_balloon'>
                            <p class='post_message'>${AutoLink(value.message.replace(/\n|\r\n|\r/g, '<br>'))}</p>
                            </div>
                            <br class='clear_balloon'/>
                            </div>
                            `);
                        } else {
                            //リプライのとき
                            $('.chat_container').append(`
                            <div class='chat_right chat' id=${value.id}>
                            <p class='post_date_right'>${value.post_time}</p>
                            <div class='right_balloon'>
                            <a href='#${value.reply_id}' class='a'>
                            <div class='show_reply'>
                            <img class='reply_img' src='../board/icon/${value.reply_user}'>
                            <div class='show_reply_to'>
                            <p class='show_reply_to_name'>${value.reply_to}</p>
                            <p class='show_reply_to_message'>${value.reply_to_message}</p>
                            </div>
                            </div>
                            </a>
                            <p class='post_message'>${AutoLink(value.message.replace(/\n|\r\n|\r/g, '<br>'))}</p>
                            </div>
                            <br class='clear_balloon'/>
                            </div>
                            `);
                        }
                    } else {
                        //画像のとき
                        $('.chat_container').append(`
                        <div class='chat_right chat' id=${value.id}>
                        <p class='post_date_right'>${value.post_time}</p>
                        <a href='images/${value.image}' rel='lightbox'>
                        <img class='post_image' src='images/${value.image}'>
                        </a>
                        </div>
                  `);
                    }
                    //相手のメッセージ
                } else {
                    if (value.image === null) {
                        if (value.reply_id === null) {
                            $('.chat_container').append(`
                            <p class='name'>${value.user_name}</p>
                            <div class='chat_left chat' id=${value.id} data-user=${value.user_number}>
                            <img class='icon_img' src='../board/icon/${value.user_number}'>
                            <div class='left_balloon'>
                            <p class='post_message'>${AutoLink(value.message.replace(/\n|\r\n|\r/g, '<br>'))}</p>
                            </div>
                            <p class='post_date_left'>${value.post_time}</p>
                            <br class='clear_balloon'/>
                            </div>
                            `);
                        } else {
                            //リプライのとき
                            $('.chat_container').append(`
                            <p class='name'>${value.user_name}</p>
                            <div class='chat_left chat' id=${value.id} data-user=${value.user_number}>
                            <img class='icon_img' src='../board/icon/${value.user_number}'>
                            <div class='left_balloon'>
                            <a href='#${value.reply_id}' class='a'>
                            <div class='show_reply'>
                            <img class='reply_img' src='../board/icon/${value.reply_user}'>
                            <div class='show_reply_to'>
                            <p class='show_reply_to_name'>${value.reply_to}</p>
                            <p class='show_reply_to_message'>${value.reply_to_message}</p>
                            </div>
                            </div>
                            </a>
                            <p class='post_message'>${AutoLink(value.message.replace(/\n|\r\n|\r/g, '<br>'))}</p>
                            </div>
                            <p class='post_date_left'>${value.post_time}</p>
                            <br class='clear_balloon'/>
                            </div>
                            `);
                        }
                    } else {
                        //画像のとき
                        $('.chat_container').append(`
                        <p class='name'>${value.user_name}</p>
                        <div class='chat_left chat' id=${value.id} data-user=${value.user_number}>
                        <img class='icon_img' src='../board/icon/${value.user_number}'>
                        <div class='chat_left chat_left_img'>
                        <a href='images/${value.image}' rel='lightbox'>
                        <img class='post_image' src='images/${value.image}'>
                        </a>
                        <p class='post_date_left'>${value.post_time}</p>
                        </div>
                        `);
                    }
                }
            });
            post_date = [];
            checknew = last['id'];
            scrollbottom();
        }
    }).fail(function(data) {
        // 取得エラー
        console.log(data);
    }).always(function(data) {});
}
//ログインユーザー取得
function login_user() {
    $.ajax({
        type: "POST",
        url: "login_user.php",
        dataType: "json"
    }).done(function(data) {
        user_name = data[0];
        user_number = data[1];
    }).fail(function() {
        // 取得エラー
    }).always(function() {
        readMessage();
        if (user_number == 0) {
            swal({
                    title: "d-salonにログイン",
                    text: "チャットの参加にはログインが必要です",
                    buttons: ["キャンセル", "ログイン"]
                })
                .then((willDelete) => {
                    if (willDelete) {
                        window.location.href = "../board/login.php";
                    } else {
                        window.location.href = "index.php";
                    }
                });
        }
    });
}
//メッセージの書き込み
function write_message() {
    var no = (new URL(document.location)).searchParams.get('no');
    var message = document.getElementById("message").value;
    if (message != '') {
        if (is_reply === false) {
            $.ajax({
                type: "POST",
                url: "write_message.php",
                data: {
                    message: message,
                    no: no,
                    token: chat_token,
                },
            }).done(function(data) {
                console.log(data);
            }).fail(function() {
                // 取得エラー
                console.log('e');
            }).always(function() {
                document.getElementById("message").value = '';
                readMessage();
            });
        } else {
            //リプライの場合
            $.ajax({
                type: "POST",
                url: "write_message.php",
                data: {
                    message: message,
                    no: no,
                    token: chat_token,
                    reply_user: reply_user,
                    reply_to: reply_to,
                    reply_to_message: reply_to_message,
                    reply_id: reply_id
                },
            }).done(function(data) {
                console.log(data);
            }).fail(function() {
                // 取得エラー
                console.log('e');
            }).always(function() {
                document.getElementById("message").value = '';
                $('#reply_icon').attr('src', ``);
                $('.reply_name').text('');
                $('.reply_message').text('');
                $('.reply').css('height', '0');
                is_reply = false;
                reply_user = null;
                reply_to = null;
                reply_to_message = null;
                reply_id = null;
                readMessage();
            });
        }
    }
}

//画像の送信
function post_img() {
    var no = (new URL(document.location)).searchParams.get('no');
    /* 画像ファイルの取得・セット */
    var fd = new FormData();
    var fd = new FormData($('#send-form').get(0));
    fd.append('no', no);
    fd.append('token', chat_token);

    $.ajax({
        type: "POST",
        url: "post_img.php",
        data: fd,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'html'
    }).done(function(data) {
        console.log(data);
    }).fail(function() {
        // 取得エラー
        console.log('e');
    }).always(function() {
        document.getElementById("message").value = '';
        $('.preview_close_icon').hide();
        $('#message').show();
        $('.preview').css('padding', '0');
        $('.preview').css('margin', '0');
        $('.upload-label').css('position', 'relative');
        $('#preview').attr('src', '');
        $('#send').attr('onclick', 'write_message();');
        $('#preview').hide();
        readMessage();
    });
}

//授業名取得
function class_name() {
    var no = (new URL(document.location)).searchParams.get('no');
    $.ajax({
        type: "POST",
        url: "class_name.php",
        data: {
            no: no,
        },
        dataType: "json"
    }).done(function(data) {
        $('.do_tweet').html(data.CourseName + '<br>' + data.Instructor);
        courseName = data.CourseName;
        instructor = data.Instructor;
    }).fail(function() {
        // 取得エラー
        console.log('e');
    }).always(function() {});
}

//自動リンク
function AutoLink(str) {
    var regexp_url = /((h?)(ttps?:\/\/[a-zA-Z0-9.\-_@:/~?%&;=+#',()*!]+))/g; // ']))/;
    var regexp_makeLink = function(all, url, h, href) {
        return '<a href="h' + href + '">' + url + '</a>';
    }
    return str.replace(regexp_url, regexp_makeLink);
}

//下に自動スクロール
function scrollbottom() {
    var element = document.documentElement;
    var bottom = element.scrollHeight - element.clientHeight;
    window.scroll(0, bottom);
}

//画像が選択されたときプレビュー表示
$('#myImage').on('change', function(e) {
    is_image = true;
    $('.preview_close_icon').show();
    $('#send').attr('onclick', 'post_img();');
    $('#message').hide();
    $('#preview').show();
    $('.preview').css('padding', '10px');
    $('.preview').css('margin', '0 auto');
    $('.upload-label').css('position', 'absolute');
    var reader = new FileReader();
    reader.onload = function(e) {
        $("#preview").attr('src', e.target.result);
    }
    reader.readAsDataURL(e.target.files[0]);
});

//画像が閉じられたとき
$('.preview_close_icon').click(function() {
    $('.preview_close_icon').hide();
    $('#message').show();
    $('.preview').css('padding', '0');
    $('.preview').css('margin', '0');
    $('.upload-label').css('position', 'relative');
    $('#preview').attr('src', '');
    $('#send').attr('onclick', 'write_message();');
    $('#preview').hide();
});

//リプライ
$(document).on("click", ".left_balloon", function(e) {
    $('.overlay').show();
    $('#reply_icon').attr('src', ``);
    $('.reply_name').text('');
    $('.reply_message').text('');
    $('.reply').css('height', '0');
    $('.reply_btn').css('left', (e.pageX - 35));
    $('.reply_btn').css('top', (e.pageY - 78));
    $('.reply_btn').fadeIn(100);
    reply_user = $(this).parent().attr('data-user');
    reply_to = $(this).parent().prev('p').text();
    reply_to_message = $(this).children('p').text();
    reply_id = $(this).parent().attr('id');
    $('.reply_btn').click(function() {
        // 処理
        is_reply = true;
        $('.overlay').hide();
        $('.reply_btn').fadeOut(100);
        $('#reply_icon').attr('src', `../board/icon/${reply_user}`);
        $('.reply_name').text(reply_to);
        $('.reply_message').text(reply_to_message);
        $('.reply').css('height', '100px');
    });

    $('.close_icon').click(function() {
        $('#reply_icon').attr('src', ``);
        $('.reply_name').text('');
        $('.reply_message').text('');
        $('.reply').css('height', '0');
        is_reply = false;
        reply_user = null;
        reply_to = null;
        reply_to_message = null;
        reply_id = null;
    });
});

$('.overlay').click(function() {
    $('.reply_btn').fadeOut(100);
    $('.delete_btn').fadeOut(100);
    $('.overlay').hide();
    $('.ellipsis_menu').fadeOut(100);
});

//送信取消
$(document).on("click", ".right_balloon", function(e) {
    $('.overlay').show();
    $('.delete_btn').css('left', (e.pageX - 35));
    $('.delete_btn').css('top', (e.pageY - 78));
    $('.delete_btn').fadeIn(100);
    delete_id = $(this).parent().attr('id');
    $('.delete_btn').click(function() {
        $('.overlay').hide();
        $('.delete_btn').fadeOut(100);
        swal({
                text: "このメッセージの送信を取り消しますか？",
                buttons: ["キャンセル", "送信取消"]
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: "POST",
                        url: "delete_message.php",
                        data: {
                            id: delete_id,
                            token: chat_token,
                        },
                    }).done(function(data) {
                        console.log(data);
                    }).fail(function() {
                        // 取得エラー
                        console.log('e');
                    }).always(function() {
                        readMessage(true);
                    });
                }
            });
    });
});

//スクロール位置
$(function() {
    var headerHeight = 55; //固定ヘッダーの高さを入れる
    $(document).on("click", "[href^='#']", function() {
        var href = $(this).attr("href");
        var target = $(href == "#" || href == "" ? 'html' : href);
        var position = target.offset().top - headerHeight;
        $("html, body").animate({ scrollTop: position }, 0, "swing");
        return false;
    });
});

//左上のボタン
$('.btn_ellipsis').click(function() {
    $('.ellipsis_menu').slideDown(100);
    $('.overlay').show();
});

//退会
$('.btn_leave').click(function() {
    var no = (new URL(document.location)).searchParams.get('no');
    $('.ellipsis_menu').hide();
    swal({
            text: "このグループを退会しますか？",
            buttons: ["キャンセル", "退会"]
        })
        .then((willDelete) => {
            if (willDelete) {
                if (willDelete) {
                    $.ajax({
                        type: "POST",
                        url: "unregister_class.php",
                        data: {
                            no: no,
                            user_number: user_number,
                        },
                        dataType: "json"
                    }).done(function(data) {}).fail(function() {}).always(function(data) {
                        window.location.href = "index.php"
                    });
                }
            }
        });
});