$(document).ready(function() {
    

    // Mobile version
    $('#mobile-menu-button').on('click', function(event) {
        $(this).prev('nav').slideToggle('slow');
    });

    // Tablet version
    $('.nav-item').hover((function() { // On mouse enter
        $(this).find('> .nav-submenu-container').removeClass('hidden');
    }), (function() { // On mouse exit
        $(this).find('> .nav-submenu-container').addClass('hidden');
    }));
});