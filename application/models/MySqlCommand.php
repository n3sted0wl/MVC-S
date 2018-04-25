<?php
    /** A Sql command to be executed against a MySql database */
    class MySqlCommand {
        private $_sqlCommand;
        private $_description;

        public function __construct(string $command, string $description) {
            $this->SetCommand($command);
            $this->SetDescrpiption($description);
        }

        /** Get the SQL command as a string */
        public function GetCommand() : string {
            return $this->_sqlCommand;
        }

        /** Set the SQL command string */
        public function SetCommand(string $command) {
            $this->_sqlCommand = $command;
        }

        /** Get the description of the query */
        public function GetDescription() {
             return $this->_description;
        }

        /** Set the description of the query */
        public function SetDescription(string $description) {
            $this->_description = $description;
        }
    }
?>