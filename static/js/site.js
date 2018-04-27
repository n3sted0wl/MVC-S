/* ===================== On Page Load ===================== */
$(document).ready(function() {
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