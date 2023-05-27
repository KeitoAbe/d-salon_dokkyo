$('input[type="submit"]').css('background-color', '#eee');
$('input[type="text"]').on('input', function() {
    var $input = $('input[type="text"]').val();
    if ($input.match(/.+@.+\..+/)) {
        $('input[type="submit"]').css('background-color', '#fdf1c8');
    } else {
        $('input[type="submit"]').css('background-color', '#eee');
    }
});