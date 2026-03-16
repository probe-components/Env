<?php
namespace Probe\Env\Traits;

trait ParseRules{
    /**
     * Parses the rules from string to an associative array
     * @param string $rules
     * @return array{default: ?string, type: ?string, values: ?array, requirements: ?array}
     */
    public static function parseRules(string $rules): array{
        // Separate / Extract the rules
        $rules = explode("|", $rules);
        // Extract the default Value for env generator (NOT USED FOR LOADER)
        $defaultValue = NULL;
        // Allowed Values for Dotenv::allowedValues() for the self::load() method
        $allowedValues = NULL;
        $expectedDataType = NULL;
        foreach($rules as $index => $rule){
            if (str_contains($rule, "default")){
                $defaultValue = explode(":", $rule)[1];
                // remove it from the $rules array
                unset($rules[$index]);
            }
            if (str_contains($rule, "values")){
                // Gets the array of allowed values, i.e values:[1,2,3] -> [1,2,3] (Still a string)
                $allowedValues = explode(":", $rule)[1];
                // Remove the square bracket from the start so [1,2,3] -> 1,2,3]
                $allowedValues = ltrim($allowedValues, "[");
                // Remove the square bracket from the end so [1,2,3] -> 1,2,3
                $allowedValues = rtrim($allowedValues, "]");
                // Turn it into an array of values by separating the string and using a comma as a delimiter, 1,2,3 -> array(1,2,3)
                $allowedValues = explode(",", $allowedValues);
                // remove it from the $rules array
                unset($rules[$index]);
            }
        }
        $rules = array_values(array: $rules);
        return [
            "default" => $defaultValue,
            "type" => $expectedDataType,
            "values" => $allowedValues,
            "requirements" => $rules,
        ];
    }
}