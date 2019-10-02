<?php namespace Standardizer\Converters;

use Standardizer\Converter;
use Standardizer\Interfaces\ConverterInterface;

/**
 * Implementa o conversor de cadastros
 */
class CadastroConverter extends Converter implements ConverterInterface
{
    // Configuration Getters
    public function getFieldsToImplode(): array { 
        return $this->standard['fields']; 
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
