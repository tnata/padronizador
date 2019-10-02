<?php namespace Standardizer\Factories;

use Stringy\Stringy as Str;

use Standardizer\Exporter;
use Standardizer\Interfaces\ParserInterface;

/**
 * Parser factory
 */
class ParserFactory
{
    public static function create(Exporter $exporter): ParserInterface
    {
        // Get the parser erp by parsing the exporter inputFilePath
        $erps = array_keys(config('parsers')->getItems());
        foreach($erps as $erp) {
            if (Str::create($exporter->getInputFilePath())->contains($erp)) {
                $parserErp = $erp;
            }
        }
        // Check if a valid converter was found
        if (!isset($parserErp)) {
            throw new \Exception('Parser não implementado!');
        }

        // Define the converter class by type
        $class = 'Standardizer\\Parsers\\'.ucfirst($parserErp).'Parser';

        // Create the parser object
        return new $class();
    }
}