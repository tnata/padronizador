<?php namespace Standardizer\Factories;

// Thyrd party lib's
use Stringy\Stringy as Str;

/**
 * Exporter factory
 */
class ConverterFactory
{
    public static function create($inputFile = null)
    {
        // Define the standard for conversion
        foreach(config('converters')->get('available') as $config) {
            if (Str::create($inputFile)->contains($config['name'])) {
                $array_config = $config;
            }
        }

        if (!isset($array_config)) {
            throw new \Exception('Conversor não implementado!');
        }

        $class = 'Standardizer\\Converters\\'.$array_config['class'];
        return new $class($inputFile, $array_config);
    }
}