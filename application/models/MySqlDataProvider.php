<?php
    /** Manages connection to and interaction with a MySql database */
    abstract class MySqlDataProvider {
        /** Execute a MySqlCommand and return the results;
         * If no connection is specified, the default configuration is used */
        public static function ExecuteMySql(
            MySqlCommand $command, 
            MySqlConnection $connection=null) : MySqlQueryResult {
            $sql = $command->GetCommand();
            $description = $command->GetDescription();

            // Validate the function input
            if (empty($sql)) { throw new Exception("SQL command is empty"); }
            $mySqlConnection = self::SetUpConnection($connection);

            // Define the data that will be returned
            $resultDataSet = array();
            $resultStatusType = "Success";
            $resultStatusDescription = 
                "The query with description [{$description}] executed successfully";

            // Execute the query using php library calls
            $mySqli = $mySqlConnection->GetMySqli();
            $mySqli->multi_query($sql);

            $storedResults = ($mySqli->store_result());
            if (!$storedResults) {
                $resultDataSet = array();
                $resultStatusType = "Failure";
                $resultStatusDescription = "Invalid SQL detected";
            } else {
                $resultDataSet = $storedResults->fetch_all(MYSQLI_ASSOC);
                try {
                    set_error_handler(function($errorNumber, $errorString) { 
                        if ($errorNumber != 2048) { throw new Exception("{$errorString}"); }
                    });
                    if (@$mySqli->next_result()) { throw new Exception("Multiple data sets received"); }
                } catch (Exception $exc) {
                    $resultStatusType = "Failure";
                    $resultStatusDescription = $exc->GetMessage();
                    $resultDataSet = array();
                }    
            }

            // Return the results of the query execution
            return new MySqlQueryResult(
                (new Status($resultStatusType, $resultStatusDescription)), 
                (new DataSet($resultDataSet))
            );
        }

        /** Execute a MySql stored procedure and return the results;
         * If no connection is specified, the default configuration is used */
        public static function ExecuteProcedure(
            MySqlStoredProcedure $procedure, 
            MySqlConnection $connection=null) : MySqlQueryResult {
            $mySqlConnection = self::SetUpConnection($connection);
        }

        /** Execute a MySql function and return the results;
         * If no connection is specified, the default configuration is used */
        public static function ExecuteFunction(
            MySqlFunction $function, 
            MySqlConnection $connection=null) : MySqlQueryResult {
            $mySqlConnection = self::SetUpConnection($connection);
        }

        /** Get a connection based off the settings in the configuration file */
        public static function GetDefaultConnection() : MySqlConnection {
            return MySqlConnection::GetConnection("default");
        }

        /** Get a valid connection for a query execution; 
         * If @connection is null or not a MySqlConnection obect, tries to get
         * the default configured connection; Throws an Exception if it fails 
         * to establish one */
        private static function SetUpConnection($connection) : MySqlConnection {
            if (is_null($connection) || ($connection instanceof MySqlConnection)) {
                $connection = self::GetDefaultConnection();
            }
            if (!$connection->IsValid()) {
                throw new Exception("Could not set up a connection for the query");
            }
            return $connection;
        }
    }
?>