<?php
    /** The result of executing a database command */
    class MySqlQueryResult {
        private $_status;
        private $_dataSet;

        public function __construct(Status $resultStatus, DataSet $resultDataSet) {
            $this->SetStatus($resultStatus);
            $this->SetDataSet($resultDataSet);            
        }

        /** Get the status of the query result */
        public function GetStatus() : Status {
            return $this->_status;
        }

        /** Set the status of the query result; Only called by the constructor */
        private function SetStatus(Status $newStatus) {
            $this->_status = $newStatus;
        }

        /** Get the DataSet the query returned */
        public function GetData() : array {
            return $this->_dataSet->GetDataArray();
        }
        
        /** Set the DataSet the query returned; Only called by the constructor */
        private function SetDataSet(DataSet $newDataSet) {
            $this->_dataSet = $newDataSet;
        } 
    }
?>