$('input[type="submit"]').css('background-color', '#eee');
$('input[type="text"]').on('input', function() {
    var $input = $('input[type="text"]').val();
    if ($input.length === 8 && $input.match(/^[0-9]+$/)) {
        $('input[type="submit"]').css('background-color', '#fdf1c8');
    } else {
        $('input[type="submit"]').css('background-color', '#eee');
    }
});