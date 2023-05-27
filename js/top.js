//
// スマートフォンかどうか判断して、スマートフォンの場合のみ処理。
//
if (IsSmartPhone()) {
    //
    // ソースが全て書き出された後に処理。
    //
    $(function() {
        //
        // 全ての『data-sp-href』属性を繰り返し処理。
        //
        $('body').find('[data-sp-href]').each(function() {
            //
            // リンクを入れ替え。
            //
            $(this).attr('href', $(this).attr('data-sp-href'));
        });
    });
}
//
// スマートフォンかどうか判断するメソッド。
//
function IsSmartPhone() {
    // デバイスの種類。
    var media = [
        'iPhone',
        'Windows Phone',
        'Android'
    ];
    var pattern = new RegExp(media.join('|'), 'i'); //デバイスの種類を正規表現にする。
    return pattern.test(navigator.userAgent); //ユーザーエージェントにデバイスが含まれるかを調べる。
}
$(".campus_system").click(function() {
    $(".link_container").toggleClass("show");
});

function profile(user_number) {
    if (user_number === 0) {
        swal({
                title: "d-salonにログイン",
                text: "全ての機能を利用するにはログインが必要です",
                showCloseButton: true,
                buttons: {
                one: {
                    text: "アカウント作成",
                    value: 1
                },
                two: {
                  text: "ログイン",
                  value: 2,
                },                
                },
                }).then( value => {
                switch (value) {                  
                    case 1:
                        window.location.href = "mail_check.php";
                        break;
                    case 2:
                        window.location.href = "login.php";
                        break;
                }
            });
    } else {
        window.location.href = "board/profile.php?user_number=" + user_number;
    }
}

function logout() {
    swal({
            title: "d-salonからログアウト",
            text: "ログアウトしてよろしいですか？",
            buttons: ["キャンセル", "ログアウト"]
        })
        .then((willDelete) => {
            if (willDelete) {
                window.location.href = "./logout.php"
            }
        });
}
