<?php
namespace Probe\Env\Commands;

use Probe\Support\Str;
use Probe\Support\Stub;

/**
 * Create a Blueprint for an env file from stub
 */
abstract class CreateEnvBlueprint{
    /**
     * Create a Env Blueprint from Stub
     * @param string $fileName The desired name for the final file
     * @param string $destinationDir Where the stub will be placed, This is relative to the current working directory
     * @param string $namespace A custom namespace for the newly generated Blueprint
     * @return void
     */
    public static function create(string $fileName, string $destinationDir = "/Config/Env/", string $namespace = "App\Config\Env"): void{
        if (isFocalApp()){
            $destinationDir = app()->basePath() . $destinationDir;
        }
        $fileBaseName = $fileName;
        $fullFileName = "{$fileBaseName}.php";

        if (!Str::endsWith($destinationDir, "/")){
            $destinationDir .= "/";
        }
        $filePath = $destinationDir . $fullFileName;
        // The blueprint file that is used to generate the template
        $envTemplate = Stub::getPath(Stub::ENV_BLUEPRINT);
    
        if (file_exists($filePath)){
            echo "{$filePath} already exists";
            exit;
        }
        if (!file_exists($envTemplate)){
            echo "Env stub does not exist.";
            exit;
        }
    
        // Get the blueprint
        $blueprint = file_get_contents(filename: $envTemplate);
        
        // Replace placeholders
        $blueprint = str_replace(search: "{{ name }}", replace: $fileBaseName, subject: $blueprint);
        $blueprint = str_replace(search: "{{ namespace }}", replace: $namespace, subject: $blueprint);
    
        // Build the template using the blueprint
        $file = fopen(filename: $filePath, mode: "w");
        fwrite(stream: $file, data: $blueprint);
        fclose(stream: $file);
    
        if (file_exists($filePath)){
            echo "Template generated: {$filePath}";
        }else{
            echo "Generation Failed, Please make sure you have sufficient permissions in: {$destinationDir}";
            exit;
        }
    }
}