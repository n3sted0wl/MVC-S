<?php
    /** Collection of Unit Tests to be executed. 
     * When a model is designed, add the unit tests to this class for execution.
     */
    class UnitTests extends Controller {
        /** Get the collection of user tests to be executed */
        public static function GetUnitTests () : array {
            // Array element structure: 
            // [ string UnitTestName => [
            //      string UnitTestSummary, 
            //      function UnitTestBody : bool ] 
            // ]
            return array(
                'Display styled output' => [
                    "Output styled messages and succeed",
                    function () {
                        echo "Beginning UnitTest() with output<br />";
                        echo self::OutError("Error message")."<br />";
                        echo self::OutSuccess("Success message")."<br />";
                        echo self::OutWarn("Warning message")."<br />";
                        echo self::OutCode("Code")."<br />";
                        return true;
                    }
                ],
                'Parse models and controllers' => [
                    "Try to parse all application code files in the MVC model. 
                    Note, this only checks basic syntax and parseability.",
                    function () {
                        $result = true;
                        $allCodeFiles = array();
                        foreach ($GLOBALS[CONFIG]["mvcfolderpathkeys"] as $key) {
                            $filePath = $GLOBALS[CONFIG]["folderpath"][$key];
                            $fileList = array_filter(
                                scandir($filePath),
                                function ($folderName) {
                                    return !(substr($folderName, 0, 1) === ".");
                                }
                            );
                            foreach ($fileList as $file) {
                                array_push($allCodeFiles, ($filePath.$file));
                            }
                        }
                        try {
                            echo "<div>Trying to parse the following files:</div>";
                            foreach ($allCodeFiles as $file) {
                                if (!file_exists($file)) {
                                    throw new Exception("File {$file} does not exist");
                                } else if (!is_readable($file)) {
                                    throw new Exception("File {$file} is not readable");
                                } else {
                                    $fileContents = file_get_contents($file);
                                    self::OutCode("Testing code file: {$file} ... ");
                                    if (!self::IsValidPHP($fileContents)) {
                                        throw new Exception("File {$file} has syntax errors");
                                    } else {
                                        self::OutSuccess("Succeeded without errors<br />");
                                    }
                                }
                            }
                        } catch (Exception $exception) {
                            self::OutError("Error encountered: ".$exception->GetMessage()."<br />");
                            echo "<a style='color:blue;font-style:italic;' href='/dev'>Go to Dev page</a>";
                            $result = false;
                        }
                        return $result;
                    }
                ],
                'Utility functions' => [
                    // Start of testing self::OverrideAssociativeArray()
                    "Test Utility functions which are globally available",
                    function() {
                        $result = true;
                        echo "Testing self::OverrideAssociativeArray()...";
                        $originalArray = array(
                            "firstELementWithoutKey",
                            "secondElementWithoutKey",
                            "firstKey" => "firstValueWithKey",
                            "secondKey" => "secondValueWithKey",
                            "firstArrayToAddTo" => array(
                                "firstArrayValueA",
                                "firstArrayValueB",
                                "firstArrayValueC",
                            ),
                            "firstArrayToReplace" => array(
                                "Should not see this",
                                "anywhere at all"
                            ),
                            "nestedArray" => array(
                                "nestedArrayElement" => array(
                                    "SecondLevelA",
                                    "SecondLevelB",
                                    "SecondLevelKey" => array(
                                        "FinalA",
                                        "FinalB"
                                    )
                                )
                            )
                        );
                        $overridingArray = array(
                            "thirdElementWithoutKey",
                            "firstArrayToAddTo" => array(
                                "firstArrayValueD"
                            ),
                            "firstArrayToReplace" => "replacement",
                            "nestedArray" => array(
                                "nestedArrayElement" => array(
                                    "SecondLevelKey" => array(
                                        "FinalC"
                                    )
                                )
                            )
                        );
                        $expectedResult = array(
                            "firstELementWithoutKey",
                            "secondElementWithoutKey",
                            "thirdElementWithoutKey",
                            "firstKey" => "firstValueWithKey",
                            "secondKey" => "secondValueWithKey",
                            "firstArrayToAddTo" => array(
                                "firstArrayValueA",
                                "firstArrayValueB",
                                "firstArrayValueC",
                                "firstArrayValueD",
                            ),
                            "firstArrayToReplace" => "replacement",
                            "nestedArray" => array(
                                "nestedArrayElement" => array(
                                    "SecondLevelA",
                                    "SecondLevelB",
                                    "SecondLevelKey" => array(
                                        "FinalA",
                                        "FinalB",
                                        "FinalC"
                                    )
                                )
                            )
                        );
                        $resultingArray = Utility::OverrideAssociativeArray($originalArray, $overridingArray);
                        if ($resultingArray === $expectedResult) {
                            self::OutSuccess("succeeded");
                        } else {
                            self::OutError("failed");
                            self::OutWarn("<br />Did not return expected results");
                            $result = false;
                        }
                        // End of testing self::OverrideAssociativeArray()

                        return $result;
                    }
                ],
                'Database connection configurations' => [
                    "Test the configuration settings to connecting to databases",
                    function() {
                        $result = false; // Since I'm testing the error handling, 
                                         // this starts as false and is set true if the error
                                         // is caught.

                        // Attempt to establish a connection with settings that
                        // aren't configured
                        echo "Testing database connection to non-existant configuration. ";
                        echo "There should be be an error output from the function:";
                        if (!MySqlConnection::GetConnection("configThatDoesn'tExist")) {
                            self::OutSuccess("...test succeeded");
                            $result = true;
                        }

                        // Now test the connection settings that are in the configuration file
                        if ($result) {
                            echo "<br /><br />Testing listed connection configurations";
                            foreach (array_keys($GLOBALS[CONFIG]['database']) as $databaseKey) {
                                echo "<br /> - '{$databaseKey}'...";
                                $currentDbSettings = $GLOBALS[CONFIG]['database'][$databaseKey];
                                if (@(new mysqli(
                                    $currentDbSettings["hostname"], 
                                    $currentDbSettings["username"], 
                                    $currentDbSettings["password"], 
                                    $currentDbSettings["name"], 
                                    intval($currentDbSettings["port"]), 
                                    $currentDbSettings["socket"]))->ping()) {
                                    self::OutSuccess("connection successful");
                                } else {
                                    self::OutError("connection failed");
                                    $result = false;
                                }
                            }
                            echo "<br />Done testing connection configurations";
                        }
                        return $result;
                    }
                ],
                'Model: MySqlConnection' => [
                    "Test the MySqlConnection class", 
                    function () {
                        $result = true;
                        try {
                            // Set how errors are managed
                            set_error_handler(function($errorNumber, $errorMessage) {
                                self::OutError("An error occured when running this test : ");
                                echo "{$errorMessage}<br />";
                                throw new Exception("Test failed");
                            }, -1);

                            // Test cases
                            echo "Testing MySqlConnection::GetConnection()...";
                            $testConnection = MySqlConnection::GetConnection("default");
                            if ($testConnection) {
                                self::OutSuccess("succeeded");
                            } else {
                                self::OutError("failed - no connection returned");
                                throw new Exception("Failed to construct connection");
                            }

                            echo "<br />Testing MySqlConnection->Validate()...";
                            $validationStatus = $testConnection->Validate();
                            if (!$validationStatus) {
                                self::OutError("failed");
                                $result = false;
                            } else {
                                self::OutSuccess("succeeded");
                            }
                            echo "<br />Running IsValid()...";
                            if ($testConnection->IsValid()) {
                                self::OutSuccess("succeeded");
                            } else {
                                self::OutWarning("failed to create a valid connection");
                            }

                        } catch (Exception $exc) {
                            $result = false;
                        }
                        return $result;
                    }
                ],
                'Model: MySqlCommand' => [
                    "Test the MySqlCommand object",
                    function() {
                        $result = true;
                        self::OutWarn("Not really sure how to test this...");
                        return $result;
                    }
                ],
                'Model: MySqlDataProvider' => [
                    "Database connection and CRUD operations",
                    function () {
                        $result = true;
                        #region ExecuteSql()
                        echo "Testing MySqlDataProvider::ExecuteSql(MySqlCommand, MySqlConnection)";
                        if ($result) {
                            echo "<br /> - Emtpy sql command...";
                            $result = false;
                            try { MySqlDataProvider::ExecuteMySql((new MySqlCommand("", "Empty sql command")));
                            } catch (Exception $exc) { $result = true; }
                            if ($result) { self::OutSuccess("succeeded"); } 
                            else { self::OutError("failed"); }
                        }

                        if ($result) {
                            echo "<br /> - Invalid sql command...";
                            $result = false;
                            $queryResult = MySqlDataProvider::ExecuteMySql((new MySqlCommand("something", "Invalid sql command")));
                            $result = $queryResult->GetStatus()->GetStatusType() == "Failure";
                            if ($result) { self::OutSuccess("succeeded"); } 
                            else { self::OutError("failed"); }
                        }

                        if ($result) {
                            echo "<br /> - Empty dataset returned...";
                            $sql = "SELECT * FROM TestTable WHERE UserName = 'NonExistent'";
                            $result = false;
                            $queryResult = MySqlDataProvider::ExecuteMySql((new MySqlCommand($sql , "Multiple datasets returned")));
                            $result = ($queryResult->GetStatus()->GetStatusType() == "Success") && 
                                      (empty($queryResult->GetData()));
                            if ($result) { self::OutSuccess("succeeded"); } 
                            else { self::OutError("failed"); }
                        }
                        #endregion

                        #region ExecuteProcedure()
                        if ($result) {
                            echo "<br />Testing MySqlDataProvider::ExecuteProcedure()";
                            echo "<br /> - Calling an undefined procedure...";
                            $result = false;
                            try {
                                $queryResult = MySqlDataProvider::ExecuteProcedure(
                                    (new MySqlProcedure("This does not exist"))
                                );
                            } catch (Exception $exc) {
                                $result = true;
                            }
                            if ($result) { self::OutSuccess("succeeded"); } 
                            else { self::OutError("failed"); }
                        }

                        if ($result) {
                            echo "<br /> - Calling procedure without parameters...";
                            try {
                                $queryResult = MySqlDataProvider::ExecuteProcedure(
                                    (new MySqlProcedure("SP_GetTestTableData"))
                                );
                                if (!$queryResult->GetStatus()->IsSuccessful()) {
                                    $result = false;
                                }
                            } catch (Exception $exc) {
                                $result = false;
                            }
                            if ($result) { self::OutSuccess("succeeded"); } 
                            else { self::OutError("failed"); }
                        }

                        if ($result) {
                            echo "<br /> - Calling procedure with parameters...";
                            try {
                                $queryResult = MySqlDataProvider::ExecuteProcedure(
                                    (new MySqlProcedure("SP_GetFilteredTestTableData", array("3")))
                                );
                                if (!$queryResult->GetStatus()->IsSuccessful()) {
                                    $result = false;
                                }
                            } catch (Exception $exc) {
                                $result = false;
                            }
                            if ($result) { self::OutSuccess("succeeded"); } 
                            else { self::OutError("failed"); }
                        }
                        #endregion

                        return $result;
                    }
                ],
                'Model: DataSet' => [
                    "Test DataSet class",
                    function() {
                        $result = true;
                        echo "Testing ValidateArray()...";
                        try {
                            // $arrayToTest = array();
                            // self::TestArrayForDataSet("Empty array", $arrayToTest);
                            $arrayToTest = array("", "");
                            self::TestArrayForDataSet("No nested record arrays", $arrayToTest);
                            $arrayToTest = array(
                                ["first" => "value", "second" => "value"],
                                ["first" => "value"]);
                            self::TestArrayForDataSet("Inconsistent record sizes", $arrayToTest);
                            $arrayToTest = array(
                                ["field" => "value"], 
                                ["anotherField" => "value"]);
                            self::TestArrayForDataSet("Inconsistent record names", $arrayToTest);
                            $arrayToTest = array(
                                ["field" => "value"], 
                                ["field" => []]);
                            self::TestArrayForDataSet("Non-string field values", $arrayToTest);
                        } catch (Exception $ex) {
                            self::OutError("failed with error: " . $ex->GetMessage());
                            $result = false;
                        }

                        echo "<br />Testing GetHeaders()...";
                        try {
                            $arrayToTest = array(
                                ["firstField" => "firstValue", "secondField" => "secondValue"],
                                ["firstField" => "firstValue", "secondField" => "secondValue"],
                                ["firstField" => "firstValue", "secondField" => "secondValue"],
                            );
                            $expectedResults = array(
                                "firstField" => "firstField", 
                                "secondField" => "secondField");
                            if ((new DataSet($arrayToTest))->GetHeaders() !== $expectedResults) {
                                throw new Exception("Failed to GetHeaders() properly");
                            } else {
                                self::OutSuccess("succeeded");
                            }
                        } catch (Exception $ex) {
                            self::OutError("failed");
                            $result = false;
                        }
                        return $result;
                    }
                ],
            );
        }

        #region Helper Functions
        public static function IsValidPHP($str) {
            // https://stackoverflow.com/questions/33530669/check-if-a-string-is-valid-php-code
            return 
                trim(shell_exec("echo " . escapeshellarg($str) . " | php -l")) 
                == "No syntax errors detected in -";
        }

        public static function TestArrayForDataSet(string $testName, array $arrayToTest) {
            echo "<br /> - {$testName}...";
            $resultStatus = (DataSet::ValidateArray($arrayToTest));
            if ($resultStatus->IsSuccessful()) { 
                throw new Exception("Failed with test: {$testName}");
            }
            self::OutSuccess("succeeded");
        }
        #endregion

        #region Styled output functions
        private static function OutError($errorMessage) {
            echo "<span class='error-message'>{$errorMessage}</span>";
        }

        private static function OutSuccess($successMessage) {
            echo "<span class='success-message'>{$successMessage}</span>";
        }

        private static function OutWarn($warningMessage) {
            echo "<span class='warning-message'>{$warningMessage}</span>";
        }

        private static function OutCode($code) {
            echo "<span class='code-output'>{$code}</span>";
        }
        #endregion
    }
?>