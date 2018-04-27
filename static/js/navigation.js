$(document).ready(function() {
    $('.nav-item, .nav-submenu-item').on('click', function(event) {
        var targetLocation = $(this).data('url');
        window.location.href = targetLocation;
    });

    $('.nav-item').hover((function() {
        // On mouse enter
        $(this).find('.nav-submenu').show();
    }), (function() {
        // On mouse exit
        $(this).find('.nav-submenu').hide();
    }));
});