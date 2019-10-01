<?php namespace Standardizer\Converters;

use Standardizer\Converter;
use Standardizer\Interfaces\ConverterInterface;

/**
 * Implementa the converter for cobranca output type 
 */
class CobrancaConverter extends Converter implements ConverterInterface
{
    private $mode;
    private $subtype;

    public function getCutTop(): int { return $this->subtype['defaults']['cut_top'];; }
    public function getCutBottom(): int { return $this->subtype['defaults']['cut_bottom'];; }
    public function getConcatEvery(): int { return $this->subtype['defaults']['concat_every']; }
    public function getConcatIndex() : int { return $this->getCutTop(); }
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
     * Sets the conversion mode for cobranca output
     *
     * @param string $mode
     * @return void
     * @throws \Exception when not valid mode was selected
     */
    public function setMode(string $mode): void {
        //Validate the modes in config array
        $found = false;
        foreach($this->config['subtypes'] as $subtype => $values) {
            if ($subtype == $mode) {
                $found = true;
            }
        }
        if (!$found) {
            throw new \Exception('Modo não permitido!');
        }
        // Set subtype config based on mode
        $this->subtype = $this->config['subtypes'][$mode];
        $this->mode = $mode;
    }

    /**
     * Get the conversion mode for cobranca output
     *
     * @return string
     * @throws \Exception when no mode was selected
     */
    public function getMode(): string {
        //Validate the modes in config array
        if (!isset($this->mode)) {
            throw new \Exception('Modo não definido!');
        }
        return $this->mode;
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
