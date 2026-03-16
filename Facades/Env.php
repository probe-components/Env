<?php
namespace Probe\Env\Facades;

use Probe\Env\Commands\CreateEnvBlueprint;
use Probe\Env\Generator;
use Probe\Env\Contracts\EnvBlueprint;

/**
 * Env Facade that integrates `probe/env` functionality
 */
class Env extends \Probe\Support\Facades\Env{
    /**
     * Generate an `.env` file from the provided blueprints: https://focalframework.com/docs/env/#generator
     * @param string $direcory
     * @param EnvBlueprint[] $envBlueprints
     * @return void
     */
    public static function generate(string $directory, array $envBlueprints): void{
        Generator::generate(directory: $directory, blueprints: $envBlueprints);
    }

    public static function createBlueprint(string $fileName, string $destinationDir = "/Config/Env/", string $namespace = "App\Config\Env"){
        return CreateEnvBlueprint::create($fileName, $destinationDir, $namespace);
    }
}