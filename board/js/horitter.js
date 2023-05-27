function iine(id, user_number) {
    var postData = { "message_id": id, "user_number": user_number };
    $.post(
        "iine.php",
        postData,
        function(data) {
            if (data != 0) {
                document.getElementById(id).textContent = " " + data;
                document.getElementById("good").innerHTML = `<div class='iine'><a href='likes.php?message_id=${id}'><p><span>${data}</span>件のいいね</p></a></div>`;
            } else {
                document.getElementById(id).textContent = "";
                document.getElementById("good").textContent = "";
            }
        });
    document.getElementById(id).classList.toggle('active');
    document.getElementById(id).classList.toggle('fas');
}

function t_iine(id, user_number) {
    var postData = { "message_id": id, "user_number": user_number };
    $.post(
        "iine.php",
        postData,
        function(data) {
            if (data != 0) {
                document.getElementById("good").innerHTML = `<div class='iine'><a href='likes.php?message_id=${id}'><p><span>${data}</span>件のいいね</p></a></div>`;
            } else {
                document.getElementById(id).textContent = "";
                document.getElementById("good").textContent = "";
            }
        });
    document.getElementById(id).classList.toggle('active');
    document.getElementById(id).classList.toggle('fas');

}

function iine_reply(id, user_number) {
    var postData = { "message_id": id, "user_number": user_number };
    $.post(
        "iine.php",
        postData,
        function(data) {
            if (data != 0) {
                $(`.${id}`).text(` ${data}`);
            } else {
                $(`.${id}`).text("");
            }
        });

    $(`.${id}`).toggleClass("active");
    $(`.${id}`).toggleClass("fas");

}

$(function() {
    var wrp = '#wrapper',
        btnOpen = '.btn_open',
        btnClose = '.btn_close',
        sld = '#slide',
        ovrly = '.overlay',
        current_scrollY;

    $(document).on('click', function(e) {
        if ($(sld).css('display') == 'block') {
            if ((!$(e.target).closest(sld).length) && (!$(e.target).closest(btnOpen).length)) {
                $(wrp).attr({ style: '' });
                $('html, body').prop({ scrollTop: current_scrollY });
                $(sld).slideUp(100);
                $(ovrly).fadeOut(100);
            }
        }
    });
});

$(function() {
    $('.btn_open').click(function() {
        var windowWidth = window.innerWidth;
        $('.btn_open').next().slideDown(100);
        $('.overlay-white').toggleClass('open');
    });
    $('.overlay-white').click(function() {
        $('.tweet_delete').css('display', 'none');
        $('.overlay-white').toggleClass('open');
    });
});

$('#myImage').on('change', function(e) {
    var reader = new FileReader();
    reader.onload = function(e) {
        $("#preview").attr('src', e.target.result);
    }
    reader.readAsDataURL(e.target.files[0]);
});