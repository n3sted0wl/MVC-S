<?php
    /** A structured set of data used to populate DataTables */
    class DataSet {
        private $_data = array();

        public function __construct(array $data) {
            if (self::ValidateArray($data)) {
                $this->_data = $data;
            } else {
                throw new Exception("Cannot construct DataSet");
            }
        }

        /** Get the array of data */
        public function GetDataArray() : array {
            return $this->_data;
        }

        /** Get the headers array of this dataset */
        public function GetHeaders() : array {
            $headers = array_keys(reset($this->_data));
            $associatedHeaders = array();
            foreach ($headers as $header) {
                $associatedHeaders[$header] = $header;
            }
            return $associatedHeaders;
        }

        /** Check if an array can be used to construct a DataSet */
        public static function ValidateArray(array $data) : Status {
            // The array must be two-dimensional and have the same keys
            // So basically, each element must be an array, and each of
            // those arrays needs to have the same keys.
            // Also, none of the values of those nested arrays can be arrays
            $statusType = "Success";
            $statusMessage = "This array can be used for creating a dataset";
            try {
                // if (empty($data)) { throw new Exception("DataSet cannot be empty"); }
                $isFirstRecord = true; 
                $previousRecordFields = array();
                foreach ($data as $record) { 
                    if (!is_array($record)) { throw new Exception("Each record must be an array"); }
                    $currentRecordFields = array_keys($record);
                    if ($isFirstRecord) {
                        $isFirstRecord = false;
                    } else {
                        // Check for consistent record sizes
                        if (count($previousRecordFields) != count($currentRecordFields))  {
                            throw new Exception("Records are of inconsistent size");
                        }
                        // Check for consistent record fields
                        if ($previousRecordFields !== $currentRecordFields) {
                            throw new Exception ("Record fields are inconsistent");
                        }
                    }
                    // Make sure all the values are strings
                    foreach ($record as $fieldName => $fieldValue) {
                        if (!is_string($fieldValue)) {
                            throw new Exception("Record field values must be strings");
                        }
                    }
                    $compareToPrevious = true;
                }    
            } catch (Exception $exc) {
                $statusType = "Failure";
                $statusMessage = $exc->GetMessage();
            }
            return new Status($statusType, $statusMessage);
        }
    }
?>