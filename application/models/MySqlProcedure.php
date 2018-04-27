<?php
    class MySqlProcedure extends MySqlCommand {
        private $_procedureName;
        private $_parameters;

        public function __construct(string $procedureName, array $parameters=null) {
            $this->SetProcedureName($procedureName);
            $this->SetParameters($parameters);
        }

        /** Set the name of the procedure to be called */
        private function SetProcedureName(string $procedureName) {
            if (!MySqlDataProvider::FunctionOrProcedureExists($procedureName)) {
                throw new Exception("Procedure named {$procedureName} does not exist");
            }
            $this->_procedureName = $procedureName;
        }

        /** Get the name of the procedure to execute */
        public function GetProcedureName() : string { return $this->_procedureName; }

        /** Set the parameters to be passed to the procedure */
        public function SetParameters($parameters) {
            if (!is_null($parameters)) {
                if ($this->ValidateParameterList($parameters)) {
                    $this->_parameters = $parameters;
                } else {
                    throw new Exception("The parameters passed in are not valid");
                }
            }
        }

        /** Get the array of parameters used in this procedure call */
        public function GetParameters() { return (is_null($this->_parameters) ? null : $this->_parameters ); }

        /** Validate the list of parameters */
        private function ValidateParameterList(array $parameters) : bool {
            $result = true;
            // Array must be one dimentional
            foreach ($parameters as $key => $value) {
                if (is_array($value)) {
                    $result = false;
                    break;
                }
            }
            return $result;
        }
    }
?>