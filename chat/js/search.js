function no(no) {
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
        } else {
            $.ajax({
                type: "POST",
                url: "class_name.php",
                data: {
                    no: no,
                },
                dataType: "json"
            }).done(function(data) {
                swal({
                        text: "以下の授業を登録しますか？\n\n" + data.Semester + "　" + data.Day + "　" + data.CourseAffiliation + "\n" + data.CourseName + "\n" + data.Instructor,
                        buttons: ["キャンセル", "登録"]
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                type: "POST",
                                url: "register_class.php",
                                data: {
                                    no: no,
                                    user_number: user_number,
                                },
                                dataType: "json"
                            }).done(function(data) {
                                if (data = 'e') {
                                    swal("この授業はすでに登録されています");
                                }
                            }).fail(function() {
                                window.location.href = "index.php";
                            }).always(function(data) {});
                        }
                    });
            }).fail(function() {}).always(function() {});
        }
    });
}