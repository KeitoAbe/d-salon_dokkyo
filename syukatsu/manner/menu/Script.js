$(function() {
    $('.hamburger').click(function() {
        $(this).toggleClass('active');
        if ($(this).hasClass('active')) {
            $('.globalMenuSp').addClass('active');
            $('.overlay').addClass('open');
        } else {
            $('.globalMenuSp').removeClass('active');
            $('.overlay').removeClass('open');
        }

    });
});
//メニュー内を閉じておく
$(function() {
    $('.globalMenuSp a[href]').click(function() {
        $('.globalMenuSp').removeClass('active');
       $('.hamburger').removeClass('active');

    });
  });

$('.overlay').click(function(){
      $('.globalMenuSp').removeClass('active');
      $('.overlay').removeClass('open');
      $('.hamburger').toggleClass('active');
});

  function logout(){
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
