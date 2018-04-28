$(document).ready(function() {
    $('.nav-item').hover((function() { // On mouse enter
        $(this).find('> .nav-submenu-container').show();
    }), (function() { // On mouse exit
        $(this).find('> .nav-submenu-container').hide();
    }));
});