<?php
    class Authentication {
        /** Check if a user is logged in */
        public static function UserIsLoggedIn() : bool {
            return true;
        }

        /** Get the list of groups the user is in */
        public static function GetUserGroups() : array {
            return array("anonymous", "registered", "administrator", "developer");
        }

        /** Check the required authentication for a view */
        public static function CheckAuthentication() {
        }

        /** Redirect to the login page if necessary */
        public static function RedirectToLogin() {
        }
    }
?>