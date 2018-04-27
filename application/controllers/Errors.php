<?php
    class Errors extends Controller {
        public static function RenderPageNotFound(string $targetUrl) {
            echo "<div>Page /{$targetUrl} not found. You may have follows an invalid link</div>";
        }

        public static function RenderServerError() {
            echo "There was a server error";
        }
    }
?>