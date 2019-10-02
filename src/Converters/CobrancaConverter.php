<?php namespace Standardizer\Converters;

use Standardizer\Converter;
use Standardizer\Interfaces\ConverterInterface;

/**
 * Implementa the converter for cobranca output type 
 */
class CobrancaConverter extends Converter implements ConverterInterface
{
    private $subtype;

    public function getCutTop(): int { return $this->subtype['defaults']['cut_top'];; }
    public function getCutBottom(): int { return $this->subtype['defaults']['cut_bottom'];; }
    public function getConcatEvery(): int { return $this->subtype['defaults']['concat_every']; }
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
}
