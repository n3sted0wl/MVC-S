
/** A wrapper for making a jQuery ajax POST request; 
 * The only required option setting is "serviceName" */
var sAjax = function(requestOptions) {
    if (!requestOptions.hasOwnProperty("serviceName")) {
        console.log("Please set the 'serviceName' option");
    } else {
        // Override options that have to be the same for each request
        requestOptions.method   = "POST";
        requestOptions.url      = "/services?serviceName=" + serviceName;
        requestOptions.dataType = "json";
        // Call the service
        $.ajax(requestOptions);
    }
}