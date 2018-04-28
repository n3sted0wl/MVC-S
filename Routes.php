
<?php
    // Define routes that the master page executes on page load
    Route::Set("dev", function() { Dev::RenderView("dev"); });
    Route::Set("DevInfo", function() { DevInfo::RenderView("DevInfo"); });
    Route::Set("UnitTests", function() { UnitTests::RenderView("UnitTests"); });
    Route::Set("Services", function() { Services::CallService(); } );

    Route::Set("index.php", function() { Home::RenderView("Home"); });
    Route::Set("About", function() { About::Renderview("About"); });

    // Manage a url that is not defined
    $targetUrl = strtolower($_GET["url"]);
    if (!in_array($targetUrl, Route::$ValidRoutes)) {
        Route::Manage404($targetUrl);
    }
?>

<?php
    // Class definitions
    class Route {
        public static $ValidRoutes = array();

        public static function Set(string $route, $actionToTake) {
            self::$ValidRoutes[] = strtolower($route);
            if (strtolower($_GET["url"]) == strtolower($route)) {
                // see .htaccess file for url rewrite rule
                // as of 04/19/2018, it"s set to set the url path to 
                // a get query parameter called "url"
                $actionToTake->__invoke();
            }
        }

        public static function Manage404(string $targetUrl) {
            Errors::RenderPageNotFound($targetUrl);
        }
    }
?>