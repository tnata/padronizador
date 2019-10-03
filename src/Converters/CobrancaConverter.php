<?php namespace Standardizer\Converters;

use Standardizer\Converter;
use Standardizer\Interfaces\ConverterInterface;

/**
 * Implementa the converter for cobranca output type 
 */
class CobrancaConverter extends Converter implements ConverterInterface
{
    public function getFieldsToImplode(): array {
        $fieldsToImplode = $this->getStandard()['fields'];
        // Add 18 dynamic fields
        for ($i=0; $i < 18; $i++) { 
            foreach($this->getStandard()['array_fields'] as $key => $fields) {
                foreach($fields as $field) {
                    $fieldsToImplode[] = "[$key][$i][$field]";
                }
            }
        }

        return $fieldsToImplode;
    }

     /**
     * Implements the converter logic
     *
     * @param array $lines Lines to be parsed
     * @return array $lines Lines after parsing
     **/
    public function convertLines(array $lines) : array
    {
        $parsedLines = [];

        foreach($lines as $lineText)
        {   
            $parsedLines[] = $this->parser->parseLine($lineText);
        }

        return $parsedLines;
    }
}
