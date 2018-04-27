<?php
    // Master page for php.test on local machine
    define('CONFIG', 'config');

    // Load the configuration in a globally accessible area
    $GLOBALS['ROOT_DIR'] = __DIR__;
    $GLOBALS[CONFIG] = json_decode(file_get_contents('application/configurations/site.json'), true);

    // Auto-load classes
    spl_autoload_register(function ($class_name) {
        // http://php.net/manual/en/function.spl-autoload-register.php
        // Look through all code file folders for class definitions
        // Currently, code files that are searched are in the Controllers
        // and Models folders
        foreach ($GLOBALS[CONFIG]["classfolderpathkeys"] as $folderKey) {
            $folderPath  = $GLOBALS[CONFIG]["folderpath"][$folderKey];
            $fileToCheck = $GLOBALS['ROOT_DIR'].'/'.$folderPath.$class_name.'.php';
            if (file_exists($fileToCheck)) { require_once ($fileToCheck); } 
        }
    });

    // Set up Exception Handling
    set_error_handler(function($errorNumber, $errorMessage) {
        throw new Exception("$errorMessage");
    });

    // Load routes
    require_once($GLOBALS[CONFIG]["filepath"]["routes"]);
?>