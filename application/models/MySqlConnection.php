<?php
    /** A connection object to be used by a MySqlDataSource object */
    class MySqlConnection {
        private $_configurationName;
        private $_host;
        private $_username;
        private $_password;
        private $_port;
        private $_databaseName;
        private $_socket;

        public function __construct(
            string $confName,
            string $host, 
            string $username, 
            string $password,
            string $dbName,
            string $port,
            string $socket) {
            $this->_configurationName = $confName;
            $this->_host = $host;
            $this->_username = $username;
            $this->_password = $password;
            $this->_databaseName = $dbName;
            $this->_port = intval($port);
            $this->_socket = $socket;
        }

        /** Get the database connection specified in the configuration file */
        public static function GetConnection(string $configurationName) : MySqlConnection {
            set_error_handler(function() {
                throw new Exception("ERROR: Could not find the connection configuration settings");
            });
            try {
                $defaultConnectionSettings = $GLOBALS[CONFIG]["database"][$configurationName];
                $newConnection = new MySqlConnection(
                    $configurationName,
                    $defaultConnectionSettings["hostname"], 
                    $defaultConnectionSettings["username"], 
                    $defaultConnectionSettings["password"], 
                    $defaultConnectionSettings["name"], 
                    intval($defaultConnectionSettings["port"]), 
                    $defaultConnectionSettings["socket"]
                );
                if (! ($newConnection)->IsValid() ) {
                    throw new Exception("Could not get connection with name {$configuraitonName}");
                }
            } catch (Exception $exception) {
                echo "<div class='exceptionMessage'>".$exception->getMessage()."</div>";
                $newConnection = null;
            }
            return $newConnection;
        }
        
        /** Check if the a connection can be established with the 
         * current configuration settings
         */
        public function Validate() : Status {
            $result = new Status("Success", "Database connection is valid");
            if (!(new mysqli(
                $this->_host,
                $this->_username,
                $this->_password,
                $this->_databaseName,
                $this->_port,
                $this->_socket
                ))->ping()) {
                $result->SetStatusType("Failure");
                $result->SetDescription("The database could not establish a connection");
            }
            return $result;
        }

        /** Check if the current configuration is valid */
        public function IsValid() : bool {
            return ($this->Validate())->IsSuccessful();
        }
    }
?>