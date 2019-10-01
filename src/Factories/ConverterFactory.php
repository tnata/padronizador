<?php namespace Standardizer\Factories;

use Standardizer\Converter;

/**
 * Exporter factory
 */
class ConverterFactory
{
    public static function create(string $inputFilePath, string $rawFilePath)
    {
        // Get the converter config
        $config = Converter::getConfig($inputFilePath);

        if (!isset($config['class'])) {
            throw new \Exception('Conversor não implementado!');
        }

        if (!file_exists($rawFilePath)) {
            throw new \Exception('Arquivo raw não encontrado!');
        }

        $class = 'Standardizer\\Converters\\'.$config['class'];
        return new $class($inputFilePath, $rawFilePath);
    }
}