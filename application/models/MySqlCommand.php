<?php
    /** A Sql command to be executed against a MySql database */
    class MySqlCommand {
        private $_sqlCommand;

        public function __construct(string $command) {
            $this->SetCommand($command);
        }

        public function GetCommand() : string {
            return $this->_sqlCommand;
        }

        public function SetCommand(string $command) {
            $this->_sqlCommand = $command;
        }
    }
?>