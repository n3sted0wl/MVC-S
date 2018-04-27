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
            $querySucceeded = $mySqli->multi_query($sql);
            if ($querySucceeded === false) { 
                $resultDataSet = array();
                $resultStatusType = "Failure";
                $resultStatusDescription = "SQL Execution Error{(empty($mySqli->error)) ? '' : ': $mySqli->error'}";
            } else { // Query executed successfully; Get the dataset
                $multiQueryDataSet = array();
                do {
                    $multiQueryData = $mySqli->store_result();
                    if ($multiQueryData) {
                        $currentDataSet = $multiQueryData->fetch_all(MYSQLI_ASSOC);
                        $multiQueryDataSet = $currentDataSet;
                        $multiQueryData->free();
                    }
                } while ($mySqli->more_results() && $mySqli->next_result());
                $resultDataSet = $multiQueryDataSet;
            }

            // Return the results of the query execution
            return new MySqlQueryResult(
                (new Status($resultStatusType, $resultStatusDescription)), 
                (new DataSet($resultDataSet))
            );
        }

        /** Check if a procedure or function is defined in the database */
        public static function FunctionOrProcedureExists(
            string $name, string $databaseName="PHP_Dev") : bool {
            $query = "SELECT COUNT(*) AS found
                        FROM information_schema.routines
                       WHERE routine_schema = '{$databaseName}'
                         AND routine_name = '{$name}';";
            $queryResult = self::ExecuteMySql((new MySqlCommand($query, "Checking if {$name} exists")));
            $data = $queryResult->GetData();
            return $data[0]["found"] == 1;
        }

        /** Execute a MySql stored procedure and return the results;
         * If no connection is specified, the default configuration is used */
        public static function ExecuteProcedure(
            MySqlProcedure $procedure, 
            MySqlConnection $connection=null) : MySqlQueryResult {
            // Set up the function return object data
            $statusType = "Success";
            $statusDescription = "Procedure {$procedure->GetProcedureName()} executed successfully";
            $returnedDataSet = array();

            $mySqlConnection = self::SetUpConnection($connection);
            $sqlString = "";
            $parameterList = "";

            // Assemble the parameters
            if (!is_null($procedure->GetParameters())) {
                foreach ($procedure->GetParameters() as $paramValue) {
                    $parameterList .= "{$paramValue}, ";
                }
                $parameterList = rtrim($parameterList, ", ");
            }

            // Set the call procedure
            $sqlString .= "CALL {$procedure->GetProcedureName()}(".$parameterList.");";

            // Call the procedure
            $sqlToExecute = new MySqlCommand($sqlString, 
                "Executing procedure {$procedure->GetProcedureName()}");
            $resultOfSql = self::ExecuteMySql($sqlToExecute);

            // Set up the results of execution; check if the result is valid
            if ($resultOfSql->GetStatus()->GetStatusType() != "Success") {
                $statusType = "Failure";
                $statusDescription = 
                    "{$procedure->GetProcedureName()} failed: "
                    ."{$resultOfSql->GetStatus()->GetDescription()}";
            } else {
                $returnedDataSet = $resultOfSql->GetData();
            }

            return (new MySqlQueryResult(
                (new Status($statusType, $statusDescription)), 
                (new DataSet($returnedDataSet))
            ));

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