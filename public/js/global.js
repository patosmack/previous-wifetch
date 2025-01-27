$(function() {
    $('.lazy').Lazy();
    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="popover"]').popover();

    window.setTimeout(function () {
        $("#alert_container").fadeTo(1000, 0).slideUp(1000, function () {
            $(this).remove();
        });
    }, 5000);
    // window.setTimeout(function () {
    //     $(".status_notification").fadeTo(1000, 0).slideUp(1000, function () {
    //         $(this).remove();
    //     });
    // }, 8000);
});

// $(function () {
//     TriggerAlertClose();
// });
//
// function TriggerAlertClose() {
//
// }
