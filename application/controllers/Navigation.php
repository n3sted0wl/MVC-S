<?php
    class Navigation extends Controller {
        public static function RenderNavigation() : string {
            // Get the default navigation tree
            $defaultNavigationTree = $GLOBALS[CONFIG]["navigation"];
            
            // Get the navigation elements specific to the user's groups
            $userNavigationTree = array();

            // Merge the navigation trees into a custom one
            $customNavigationTree = Utility::OverrideAssociativeArray(
                $defaultNavigationTree, $userNavigationTree);

            // Render the navigation markup
            $navigationMarkup = "<nav>".
                self::GetNavigationMarkup($customNavigationTree, false).
                "</nav><div id='mobile-menu-button'>".
                "<div></div><div></div><div></div></div>";

            return $navigationMarkup;
        }

        private static function GetNavigationMarkup(
            array $navigationTree, 
            bool $isSubMenu=true) : string {
            $newMarkup = "";
            if ($isSubMenu) {
                $newMarkup .= "<div class='nav-submenu-container hidden'>";
            }
            foreach ($navigationTree as $navKey => $navValue) {
                if (is_array($navValue)) {
                    $newMarkup .= 
                        "<div class='nav-item".
                        (($isSubMenu) ? " submenu-item" : "" ).
                        " has-submenu'>".
                        "<span class='nav-item-text'>{$navKey}</span>".
                        self::GetNavigationMarkup($navValue).
                        "</div>";
                } else if (is_string($navValue)) {
                    $newMarkup .= "<div class='nav-item". 
                        (($isSubMenu) ? " submenu-item" : "" )
                        ."'><span class='nav-item-text'>".
                        "<a class='nav-menu-link' href='".$navValue."'>".$navKey."</a>".
                        "</span></div>";
                }
            }
            if ($isSubMenu) {
                $newMarkup .= "</div>";
            }
            return $newMarkup;
        }
    }
?>