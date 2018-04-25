<?php
    /** Manages connection to and interaction with a MySql database */
    abstract class MySqlDataProvider {
        /** Execute a MySqlCommand and return the results;
         * If no connection is specified, the default configuration is used */
        public static function ExecutMySql(
            MySqlCommand $command, 
            MySqlConnection $connection=null) : MySqlQueryResult {
            $mySqlConnection = self::SetUpConnection($connection);
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