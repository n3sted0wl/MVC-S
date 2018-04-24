
<?php
    // Define routes that the master page executes on page load
    Route::Set("dev" , function() { Dev::RenderView("dev"); });
    Route::Set("UnitTests", function() { UnitTests::RenderView("UnitTests"); });
?>

<?php
    // Class definitions
    class Route {
        public static $ValidRoutes = array();

        public static function Set(string $route, $actionToTake) {
            self::$ValidRoutes[] = $route;
            if (strtolower($_GET["url"]) == strtolower($route)) {
                // see .htaccess file for url rewrite rule
                // as of 04/19/2018, it"s set to set the url path to 
                // a get query parameter called "url"
                $actionToTake->__invoke();
            }
        }
    }
?>