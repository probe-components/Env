<?php
namespace Probe\Env;

use Exception;
use InvalidArgumentException;
use Probe\Env\Contracts\EnvBlueprint;
use Probe\Support\Str;
use ReflectionClass;


/**
 * Generate an `.env` file template by running `Probe\Env\Generator::generate()`
 */
abstract class Generator{
    /**
     * Generate a `.env` file template at the desired `$directory`.
     * * A file called `.generatedEnv` will be created if there is an existing `.env` file in the specified `$directory`, this is intentional to prevent any data loss.
     * @param EnvBlueprint[] $blueprints An array of `ENUMs` that implement the `EnvBlueprint` Contract.
     * @param string $directory The target directory the `.env` file should be generated in, I.E `$_SERVER["DOCUMENT_ROOT"]`
     * @return void
     */
    public static function generate(array $blueprints, string $directory): void{
        // Append a slash at the end of the $direcory just in case the directory provided does not end with one
        if (!Str::endsWith($directory, "/")){
            $directory .= "/";
        }
        $envVariableNames = [];
        foreach($blueprints as $blueprint){
            if (!is_subclass_of(object_or_class: $blueprint, class: EnvBlueprint::class, allow_string: true)){
                throw new InvalidArgumentException('All of the $blueprints must implement ' . EnvBlueprint::class);
            }
            // Just because it implements the interface, it does not mean its an enum, so check if it is and fail hard if it is not
            if(!(new ReflectionClass(objectOrClass: $blueprint)->isEnum())){
                throw new InvalidArgumentException('All of the $blueprints must be Enums.');
            }
            // Merge the existing variables and remove duplicates as some tools may require the same variable
            $envVariableNames = array_unique(array_merge($envVariableNames, array_column(array: $blueprint::cases(), column_key: "value")));
        }
        
        // Append an equals symbol for each variable and add a new line
        $env = implode("\n", array_map(fn ($item) => $item . '=""', $envVariableNames));
        $envFileName = match(true){
            file_exists($directory . ".env") => ".generatedEnv",
            default => ".env",
        };
        $fullEnvPath = $directory . $envFileName;
        // Check if there is a .generatedEnv to avoid overwriting it.
        if (file_exists($fullEnvPath)){
            echo "An .env and a .generatedEnv file already exist in: {$directory}";
            return;
        }
        $file = fopen($fullEnvPath, "w");
        fwrite($file, $env);
        fclose($file);
        if (file_exists($fullEnvPath)){
            echo "Env Generated: {$fullEnvPath}";
        }else{
            echo "Env generation failed, Please make sure you have the required permissions in the following directory: {$directory}";
        }
    }
}