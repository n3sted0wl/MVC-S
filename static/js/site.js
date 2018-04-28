/* ===================== On Page Load ===================== */
$(document).ready(function() {
    // Size the view to fit the footer to the bottom
    var windowHeight = $(window).height();
    var navHeight = $('nav').outerHeight(true);
    var footerHeight = $('footer').outerHeight(true);
    var viewHeight = (windowHeight - navHeight - footerHeight);
    $('.view').css('min-height', ((viewHeight - 15) + "px"));

    // Set up functionalities
    $('.link-button').on('click', function(event){
        var targetUrl = $(this).data('url');
        window.location.href = targetUrl;
    }); 
});

/* ===================== Function definitions ===================== */
/** A wrapper for making a jQuery ajax POST request; 
 * The only required option setting is "serviceName" */
var PerformService = function(requestOptions) {
    if (!requestOptions.hasOwnProperty("serviceName")) {
        console.log("Please set the 'serviceName' option");
    } else {
        // Override options that have to be the same for each request
        requestOptions.method   = "POST";
        requestOptions.url      = "/services?serviceName=" + requestOptions.serviceName;
        requestOptions.dataType = "json";
        // Call the service
        $.ajax(requestOptions);
    }
}