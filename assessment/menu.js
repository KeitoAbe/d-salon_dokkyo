window.onload = function () {
    var nav = document.getElementById('nav-wrapper');
    var hamburger = document.getElementById('js-hamburger');
    var blackBg = document.getElementById('js-black-bg');
    hamburger.addEventListener('click', function () {
        nav.classList.toggle('open');
    });
    blackBg.addEventListener('click', function () {
        nav.classList.remove('open');
    });
};

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