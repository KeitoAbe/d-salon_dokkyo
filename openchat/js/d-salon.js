function logout() {
    swal({
            title: "d-salonからログアウト",
            text: "ログアウトしてよろしいですか？",
            buttons: ["キャンセル", "ログアウト"]
        })
        .then((willDelete) => {
            if (willDelete) {
                window.location.href = "logout.php"
            }
        });
}

function slide_menu(no, user_number) {
    $.ajax({
        type: "POST",
        url: "class_name.php",
        data: {
            no: no,
        },
        dataType: "json"
    }).done(function(data) {
        swal({
                text: "以下の授業登録を解除しますか？\n\n" + data.Semester + "　" + data.Day + "　" + data.CourseAffiliation + "\n" + data.CourseName + "\n" + data.Instructor,
                buttons: ["キャンセル", "解除"],
                dangerMode: true
            })
            .then((willDelete) => {
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
                } else {
                    $('.tweet_delete').css('display', 'none');
                    $('.overlay-white').toggleClass('open');
                }
            });
    }).fail(function() {}).always(function() {});
}

function chat(no) {
    location.href = "chat.php?no=" + no;
}

function btn_menu(value_no) {
    var windowWidth = window.innerWidth;
    $('.btn_' + value_no).next().slideDown(100);
    $('.overlay-white').toggleClass('open');
}

$(function() {
    $('.overlay-white').click(function() {
        $('.tweet_delete').css('display', 'none');
        $('.overlay-white').toggleClass('open');
    });
});