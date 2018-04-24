<?php
    class Utility {
        /** Merge two (possibly associative) arrays with the second array overridding
         * any conflicting keys from the original array. This function only adds to and overrides
         * values from the original array. No elements are lost unless they are overridden.
         */
        public static function OverrideAssociativeArray (array $originalArray, array $overrideArray) : array {
            // Add in all elements that do not have explicitly- specified keys that are strings
            $newArray = array_unique(array_filter(
                array_merge($originalArray, $overrideArray), 
                function($key) {
                    return (!is_string($key));
                }, ARRAY_FILTER_USE_KEY
            ));
    
            // Loop processing all elements from the original array
            foreach (
                array_filter($originalArray, function($key) { return is_string($key); }, ARRAY_FILTER_USE_KEY) 
                as $originalKey => $originalValue) {
                if (!is_array($originalValue)) {
                    $newArray[$originalKey] = // Assign either the original or overriding value, if one exists
                        (!key_exists($originalKey, $overrideArray)) ? 
                        $originalArray[$originalKey] : // Key does not exist in override array
                        $overrideArray[$originalKey];  // Key does exist in override array
                } else { // The value is an array. Do some kind of recursion
                    $newArray[$originalKey] = // Assign either the original or overriding value, if one exists
                        (!key_exists($originalKey, $overrideArray)) ? 
                        $originalArray[$originalKey] : // Key does not exist in override array
                        ((is_array($overrideArray[$originalKey])) ? // Key exists in override array
                            self::OverrideAssociativeArray( // Recurse when original and new values are arrays
                                $originalArray[$originalKey], // Original array
                                $overrideArray[$originalKey]) : // Overriding value is an array; Recurse
                            $overrideArray[$originalKey]);  // Overriding value is not an array; Replace
                }
            }
    
            // Add in fields that are not in the original array
            foreach (
                array_filter($overrideArray, function($key) { return is_string($key); }, ARRAY_FILTER_USE_KEY) 
                as $newKey => $newValue) {
                if (!key_exists($newKey, $originalArray)) {
                    $newArray[$newKey] = $newValue;
                }
            }
            return $newArray;
        }
    }
?>