<?php
namespace Probe\Env\Contracts;

use Dotenv\Dotenv;

/**
 * Env Blueprint for creating env blueprints, these can be used in the Kernel to generate a `.env` file and also to set rules for your custom environment variables.
 */
interface EnvBlueprint{
    /**
     * Define rules for the env loader
     * * Returns `NULL` when there are no rulesets for the environment variable
     * @return string|null
     */
    public function rules(): ?string;

    /** Outline the required functions to make sure only enums are compatible. */
    public static function cases();

    public static function parseRules(string $rules): array;
    public static function loadFromDotEnvInstance(Dotenv $envLoader): void;
}