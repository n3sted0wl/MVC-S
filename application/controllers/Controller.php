<?php
    class Controller {
        public static function RenderView($viewToRender) {
            // TODO: Manage Authentication 

            // Try to load view-specific configuraiton files
            Controller::LoadConfigurationFile($viewToRender);

            // Include globally available classes
            Controller::LoadUniversalClasses();

            // Load scripts and styles
            Controller::LoadUniversalScripts();
            Controller::LoadViewScript($viewToRender);
            Controller::LoadUniversalStyles();
            Controller::LoadViewStyle($viewToRender);

            // TODO: Load the Universal Header like navigation and stuff

            // Load the view
            $filePathForView = $GLOBALS[CONFIG]["folderpath"]["views"].$viewToRender.'.php';
            if (file_exists($filePathForView)) {
                require_once ($filePathForView); // Executes the associated script in the View folder
            } else {
                throw new Exception("Failed to load view: $viewToRender");
            }

            // TODO: Load the Universal Footer like contact information and copyright
        }

        public static function LoadConfigurationFile($configurationName) {
            $configurationFilePath = $GLOBALS[CONFIG]["folderpath"]["configurations"].$configurationName.'.json';
            if (file_exists($configurationFilePath)) {
                $oldSettings = $GLOBALS[CONFIG];
                $pageSpecificSettings = json_decode(file_get_contents($configurationFilePath), true);
                $newSettings = Utility::OverrideAssociativeArray($oldSettings, $pageSpecificSettings);
                $GLOBALS[CONFIG] = $newSettings;
            }
        }

        /** Load globally available scripts */ 
        public static function LoadUniversalClasses() {
            $classFiles = $GLOBALS[CONFIG]["globallyAvailableClassFiles"];
            foreach ($classFiles as $fileName) {
                $filePath = $GLOBALS[CONFIG]["folderpath"]["models"].$fileName.'.php';
                require_once($filePath);
            }
        }

        /** Link universally available scripts and libraries */
        public static function LoadUniversalScripts() {
            $resourcesFolder = $GLOBALS[CONFIG]["folderpath"]["resources"];
            $thirdPartyLibraries = self::GetThirdPartyLibraries($resourcesFolder);

            foreach ($thirdPartyLibraries as $library) {
                $directory = $resourcesFolder.$library;
                foreach (glob($directory.'/*.js') as $filename) {
                    echo "<script src='/{$filename}'></script>";
                }
            }

            $universalScript = $GLOBALS[CONFIG]["folderpath"]["scripts"].'site.js';
            if (file_exists($universalScript)) { // My script
                echo "<script src='/{$universalScript}'></script>";
            } 
        }

        /** Link universally available styles */
        public static function LoadUniversalStyles() {
            $resourcesFolder = $GLOBALS[CONFIG]["folderpath"]["resources"];
            $thirdPartyLibraries = self::GetThirdPartyLibraries($resourcesFolder);

            foreach ($thirdPartyLibraries as $library) {
                $directory = $resourcesFolder.$library;
                foreach (glob($directory.'/*.css') as $filename) {
                    echo "<link rel='stylesheet' type='text/css' href='/{$filename}'>";
                }
            }

            $universalStyleSheet = $GLOBALS[CONFIG]["folderpath"]["stylesheets"].'site.css';
            if (file_exists($universalStyleSheet)) {
                echo "<link rel='stylesheet' type='text/css' href='/{$universalStyleSheet}'>";
            }
        }

        /** Link the script associated with a view */
        public static function LoadViewScript(string $viewToRender) {
            $filePathForScript = $GLOBALS[CONFIG]["folderpath"]["scripts"].$viewToRender.'.js';
            if (file_exists($filePathForScript)) {
                echo "<script src='/{$filePathForScript}'></script>";
            } 
        }

        /** Link the styleesheet associated with a view */
        public static function LoadViewStyle(string $viewToRender) {
            $filePathForStyle = $GLOBALS[CONFIG]["folderpath"]["stylesheets"].$viewToRender.'.css';
            if (file_exists($filePathForStyle)) {
                echo "<link rel='stylesheet' type='text/css' href='/{$filePathForStyle}'>";
            } 
        }

        /** Get the list of third party libraries in the resources folder */
        public static function GetThirdPartyLibraries($resourcesFolder) {
            $libraryList = array_filter(
                scandir($resourcesFolder),
                function ($folderName) {
                    return !(substr($folderName, 0, 1) === ".");
                }
            );

            // Move libraries that need to be prioritized to the front
            $librariesToPrioritize = ['jQuery'];
            $modifiedList = array_diff($libraryList, $librariesToPrioritize);
            foreach ($librariesToPrioritize as $library) { 
                array_unshift($modifiedList, $library);
            }

            return $modifiedList;
        }
    }
?>