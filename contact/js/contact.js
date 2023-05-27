var message = $('#message').text();
var date = new Date().toLocaleString();
$.ajax({
    type: "POST",
    url: "https://script.google.com/macros/s/AKfycbz8bmTUhluPtNH7q-NNhEtiqepG7CT2AGtcclxdO_701LwndoINsSDKU64FQxLv84VtLQ/exec",
    data: {
        message: message,
        date: date,
    },
    dataType: "json"
}).done(function(data) {}).fail(function() {}).always(function() {

});