/* Alerts */
var close = document.querySelectorAll('[data-close="alert"]');
for (var i = 0; i < close.length; i++) {
    close[i].onclick = function(){
        var div = this.parentElement;
        div.style.opacity = '0';
        setTimeout(function(){div.style.display = 'none';}, 400);
    }
}

$(function() {
    $('.lazy').Lazy();
    // $('[data-toggle="tooltip"]').tooltip();
    // $('[data-toggle="popover"]').popover();
    //
    window.setTimeout(function () {
        $(".alert").fadeTo(1000, 0).slideUp(1000, function () {
            $(this).remove();
        });
    }, 5000);
    // window.setTimeout(function () {
    //     $(".status_notification").fadeTo(1000, 0).slideUp(1000, function () {
    //         $(this).remove();
    //     });
    // }, 8000);
});
