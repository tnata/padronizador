<?php namespace Standardizer\Factories;

use Standardizer\Exporter;

/**
 * Exporter factory
 */
class ExporterFactory
{
    public static function create($inputFile)
    {
        return new Exporter($inputFile);
    }
}