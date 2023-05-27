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
    if (user_number == 0) {
        swal({
                title: "d-salonにログイン",
                text: "チャットの参加にはログインが必要です",
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
            }
});


function no(no) {
    location.href = "chat.php?no=" + no;
}

function profile(user_number) {
    if (user_number == 0) {
        swal({
                title: "d-salonにログイン",
                text: "チャットの参加にはログインが必要です",
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
        window.location.href = "../board/profile.php?user_number=" + user_number;
    }
}