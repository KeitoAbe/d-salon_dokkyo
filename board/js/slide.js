$(function() {
    $('.home-icon').click(function() {
        $('.menu').toggleClass('open');
        $('.overlay').toggleClass('open');
    });
    $('.overlay').click(function() {
        $('.menu').toggleClass('open');
        $('.overlay').toggleClass('open');
    });
});