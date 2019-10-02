<?php namespace Standardizer\Factories;

use Standardizer\Exporter;

/**
 * Exporter factory
 */
class ExporterFactory
{
    public static function create($inputFilePath)
    {
        if (!file_exists($inputFilePath)) {
            throw new \Exception('Arquivo de input n�o encontrado!');
        }

        return new Exporter($inputFilePath);
    }
}