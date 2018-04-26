<?php
    class Services extends Controller {
        /** Gets a json object with information from an 
         * asynchronous webservice. */
        public static function CallService() {
            // Prepare the object to return
            $serviceResult = array(
                "status" => "Success",
                "message" => "The request succeeded",
            );

            // Put the serviceName in the query string 
            // so I can call the service through a GET request
            $serviceName = $_GET["serviceName"];
            extract($_POST);
            
            // Perform the service
            $serviceResult["serviceName"] = $serviceName;
            switch($serviceName) {
                // Add services here as cases

                default:
                    // Default for when a serviceName is not specified
                    $serviceResult["status"] = "Error";
                    $serviceResult["message"] = "No requested service";
                    break;
            }

            // Return the results of the data as a JSON object
            echo json_encode($serviceResult);
        }
    }
?>