<?php
namespace Probe\Env\Traits;

use Dotenv\Dotenv;


trait LoadFromDotEnvInstance{
    public static function loadFromDotEnvInstance(Dotenv $envLoader): void{
        foreach(self::cases() as $envVariable){
            if ($envVariable->rules()){
                $rules = self::parseRules(rules: $envVariable->rules());
                $validator = $envLoader->required(variables: $envVariable->value);
                foreach($rules["requirements"] as $rule){
                    // If its not the values rule
                    if (!str_contains($rule, "values")){
                        match($rule){
                            "boolean" => $validator->isBoolean(),
                            "int", "integer" => $validator->isInteger(),
                            default => $validator->{$rule}(),
                        };
                    }
                }
                if ($rules["values"]){
                    $validator->allowedValues(choices: $rules["values"]);
                }
            }
        }
    }
}