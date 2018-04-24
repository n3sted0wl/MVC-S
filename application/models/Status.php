<?php
    /** A Status object that you can attach a message to */
    class Status {
        public function __construct(
            string $statusType="Success", string $description="") {
            $this->SetStatusType($statusType);
            $this->SetDescription($description);
        }

        #region Get/Set methods
        public function GetStatusType() : string {
            return $this->_statusType;
        }

        public function SetStatusType(string $statusType) {
            $this->_statusType = $statusType;
        }

        public function GetDescription() {
            return $this->_description;
        }

        public function SetDescription(string $description) {
            $this->_description = $description;
        }
        #endregion

        #region Public Funciton
        /** Check if the current status is successful */
        public function IsSuccessful () : bool {
            return $this->_statusType === "Success";
        }
        #endregion

        #region Private Fields
        private $_statusType;
        private $_description;
        #region 
    }
?>