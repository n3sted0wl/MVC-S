<?php
    /** Manages connection to and interaction with a MySql database */
    abstract class MySqlDataProvider {
        public static function ExecutMySql(
            MySqlCommand $command, 
            MySqlConnection $connection=null) : DataSet {
            throw new Exception ("Not implemented");
        }

        public static function ExecuteStoredProcedure(
            MySqlStoredProcedure $procedure, 
            MySqlConnection $connection=null) : DataSet {
            throw new Exception ("Not implemented");
        }

        public static function ExecuteFunction(
            MySqlFunction $function, 
            MySqlConnection $connection=null) : DataSet {
            throw new Exception ("Not implemented");
        }

        /** Get a connection based off the settings in the configuration file */
        public static function GetDefaultConnection() : MySqlConnection {
            return MySqlConnection::GetConnection("default");
        }
    }
?>