function reply(message_id, user_number) {
    if (user_number === 0) {
        swal({
                title: "d-salonにログイン",
                text: "返信するにはログインが必要です",
                buttons: {
                    one: {
                        text: "アカウント作成",
                        value: 1
                    },
                    two: {
                      text: "ログイン",
                      value: 2,
                    },
                } }).then( value => {
                    switch (value) {                  
                        case 1:
                            window.location.href = "../mail_check.php";
                            break;
                        case 2:
                            window.location.href = "../login.php";
                            break;
                    }
                });
    } else {
        location.href = 'reply.php?message_id=' + message_id;
    }
}

function iine_login(message_id, user_number) {
    if (user_number === 0) {
        swal({
                title: "d-salonにログイン",
                text: "いいねするにはログインが必要です",
                buttons: {
                    one: {
                        text: "アカウント作成",
                        value: 1
                    },
                    two: {
                      text: "ログイン",
                      value: 2,
                    },
                } }).then( value => {
                    switch (value) {                  
                        case 1:
                            window.location.href = "../mail_check.php";
                            break;
                        case 2:
                            window.location.href = "../login.php";
                            break;
                    }
                });
    } else {
        iine(message_id, user_number);
    }
}

function iine_reply_login(message_id, user_number) {
    if (user_number === 0) {
        swal({
                title: "d-salonにログイン",
                text: "いいねするにはログインが必要です",
                buttons: {
                    one: {
                        text: "アカウント作成",
                        value: 1
                    },
                    two: {
                      text: "ログイン",
                      value: 2,
                    },
                } }).then( value => {
                    switch (value) {                  
                        case 1:
                            window.location.href = "../mail_check.php";
                            break;
                        case 2:
                            window.location.href = "../login.php";
                            break;
                    }
                });
    } else {
        iine_reply(message_id, user_number);
    }
}

function t_iine_login(message_id, user_number) {
    if (user_number === 0) {
        swal({
                title: "d-salonにログイン",
                text: "いいねするにはログインが必要です",
                buttons: {
                    one: {
                        text: "アカウント作成",
                        value: 1
                    },
                    two: {
                      text: "ログイン",
                      value: 2,
                    },
                } }).then( value => {
                    switch (value) {                  
                        case 1:
                            window.location.href = "../mail_check.php";
                            break;
                        case 2:
                            window.location.href = "../login.php";
                            break;
                    }
                });
    } else {
        t_iine(message_id, user_number);
    }
}
$(function() {
    $('.selmodal').selModal();
});

function profile(user_number) {
    if (user_number === 0) {
        swal({
                title: "d-salonにログイン",
                text: "全ての機能を利用するにはログインが必要です",
                buttons: {
                    one: {
                        text: "アカウント作成",
                        value: 1
                    },
                    two: {
                      text: "ログイン",
                      value: 2,
                    },
                } }).then( value => {
                    switch (value) {                  
                        case 1:
                            window.location.href = "../mail_check.php";
                            break;
                        case 2:
                            window.location.href = "../login.php";
                            break;
                    }
                });
    } else {
        location.href = 'profile.php?user_number=' + user_number;
    }
}

function post(user_number) {
    if (user_number === 0) {
        swal({
                title: "d-salonにログイン",
                text: "投稿するにはログインが必要です",
                buttons: {
                    one: {
                        text: "アカウント作成",
                        value: 1
                    },
                    two: {
                      text: "ログイン",
                      value: 2,
                    },
                } }).then( value => {
                    switch (value) {                  
                        case 1:
                            window.location.href = "../mail_check.php";
                            break;
                        case 2:
                            window.location.href = "../login.php";
                            break;
                    }
                });
    } else {
        location.href = 'post.php';
    }
}

function delete_confirm(message_id,reply_id) {
    $('.tweet_delete').css('display', 'none');
    $('.overlay-white').toggleClass('open');
    swal({
            title: "投稿を削除",
            text: "この投稿を削除してよろしいですか？",
            buttons: ["キャンセル", "削除"],
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                window.location.href = "delete.php?message_id=" + message_id+"&reply_id="+ reply_id;
            }
        });
}

function report_confirm(message_id) {
    $('.tweet_delete').css('display', 'none');
    $('.overlay-white').toggleClass('open');
    swal({
            title: "投稿を通報",
            text: "この投稿を通報してよろしいですか？",
            buttons: ["キャンセル", "通報"],
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {}
        });
}

function logout() {
    swal({
            title: "d-salonからログアウト",
            text: "ログアウトしてよろしいですか？",
            buttons: ["キャンセル", "ログアウト"]
        })
        .then((willDelete) => {
            if (willDelete) {
                window.location.href = "../logout.php"
            }
        });
}

function profile_question() {
    $(".message_array_question").css("display", "block");
    $(".message_array_reply").css("display", "none");
    $(".message_array_likes").css("display", "none");
    $(".profile-tab li").css("color", "rgb(91, 112, 131)");
    $(".profile-tab li").css("border-bottom", "none");
    $(".profile-tab li:nth-of-type(1)").css("color", "#94847b");
    $(".profile-tab li:nth-of-type(1)").css("border-bottom", "2px solid #94847b");
}

function profile_reply() {
    $(".message_array_question").css("display", "none");
    $(".message_array_reply").css("display", "block");
    $(".message_array_likes").css("display", "none");
    $(".profile-tab li").css("color", "rgb(91, 112, 131)");
    $(".profile-tab li").css("border-bottom", "none");
    $(".profile-tab li:nth-of-type(2)").css("color", "#94847b");
    $(".profile-tab li:nth-of-type(2)").css("border-bottom", "2px solid #94847b");

}

function profile_likes() {
    $(".message_array_question").css("display", "none");
    $(".message_array_reply").css("display", "none");
    $(".message_array_likes").css("display", "block");
    $(".profile-tab li").css("color", "rgb(91, 112, 131)");
    $(".profile-tab li").css("border-bottom", "none");
    $(".profile-tab li:nth-of-type(3)").css("color", "#94847b");
    $(".profile-tab li:nth-of-type(3)").css("border-bottom", "2px solid #94847b");
}

