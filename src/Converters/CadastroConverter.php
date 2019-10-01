<?php namespace Standardizer\Converters;

use Standardizer\Converter;
use Standardizer\Interfaces\ConverterInterface;

/**
 * Implementa o conversor de cadastros
 */
class CadastroConverter extends Converter implements ConverterInterface
{
    /**
     * Execute the conversor and generates output
     *
     * @param Type $var Description
     * @return bool
     * @throws conditon
     **/
    public function run() : bool
    {
        // Create file for converted output
        $this->outputFile = Filesystem::createResource(
            $this->outputFolder.basename($this->outputFilePath)
        );

        // Get the lines from input file
        $lines = Filesystem::getLines($this->inputFilePath);

        //Get the total number of lines in the raw file
        $fileLines = countLines($this->inputFilePath);

        return true;
    }

    public function getCutTop(): int { return $this->config['defaults']['cut_top']; }
    public function getCutBottom(): int { return $this->config['defaults']['cut_bottom']; }
    public function getConcatEvery(): int { return $this->config['defaults']['concat_every']; }
    public function getConcatIndex() : int { return $this->getCutTop(); }
    public function getFieldsToImplode(): array { 
        return $this->getStandard()['fields']; 
    }

    /**
     * Function that implements the content parser
     *
     * @param array $content Content to be parsed
     * @return type
     * @throws conditon
     **/
    public function parser() : array
    {
        //

        return [];
    }
}
