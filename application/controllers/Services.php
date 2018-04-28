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
                    $serviceResult["message"] = "Unrecognized service requested";
                    break;
            }

            // Return the results of the data as a JSON object
            echo json_encode($serviceResult);
        }

        public static function RenderNavigation() : string {
            // Get the default navigation tree
            $defaultNavigationTree = $GLOBALS[CONFIG]["navigation"];
            
            // Get the navigation elements specific to the user's groups
            $userNavigationTree = array();

            // Merge the navigation trees into a custom one
            $customNavigationTree = Utility::OverrideAssociativeArray(
                $defaultNavigationTree, $userNavigationTree);

            // Render the navigation markup
            $navigationMarkup = "<nav>".
                self::GetNavigationMarkup("", $customNavigationTree, false).
                "</nav>";

            return $navigationMarkup;
        }

        private static function GetNavigationMarkup(
            string $markup, array $navigationTree, 
            bool $isSubMenu=true) : string {
            $newMarkup = $markup;
            if ($isSubMenu) {
                $newMarkup .= "<div class='nav-submenu-container'>";
            }
            foreach ($navigationTree as $navKey => $navValue) {
                if (is_array($navValue)) {
                    $newMarkup .= 
                        "<div class='nav-item".
                        (($isSubMenu) ? " submenu-item" : "" ).
                        " has-submenu'>".
                        "<span class='nav-item-text'>{$navKey}</span>".
                        self::GetNavigationMarkup("", $navValue).
                        "</div>";
                } else if (is_string($navValue)) {
                    $newMarkup .= "<div class='nav-item". 
                        (($isSubMenu) ? " submenu-item" : "" )
                        ."'><span class='nav-item-text'>".
                        "<a class='nav-menu-link' href='".$navValue."'>".$navKey."</a>".
                        "</span></div>";
                }
            }
            if ($isSubMenu) {
                $newMarkup .= "</div>";
            }
            return $newMarkup;
        }
    }
?>