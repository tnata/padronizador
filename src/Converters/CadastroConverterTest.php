<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use Standardizer\Converters\CadastroConverter;
use Standardizer\Converter;

final class CadastroConverterTest extends TestCase
{
    private $fileToConvert = 'cadastro.xls';
    private $fileToConvertUniondata = 'tests/assets/cadastro/uniondata/cadastro.xls';
    private $fileToConvertCarsoft = 'tests/assets/cadastro/carsoft/cadastro.xls';
    private $testFile = 'tests/assets/test.txt';
    private $rawFile;

    protected function setUp() : void
    {
        $this->rawFile = $this->testFile;
    }

    public function testCanICreateACadastroConverterInstance()
    {
        $converter = Standardizer\Factories\ConverterFactory::create(
            $this->fileToConvert, 
            $this->rawFile
        );
        $this->assertInstanceOf(
            CadastroConverter::class,
            $converter
        );
        return $converter;
    }

    /**
     * @depends testCanICreateACadastroConverterInstance
     */
    public function testCanIGetAValidStandardConfig(CadastroConverter $converter)
    {
        $this->assertArrayHasKey('fields', $converter->getStandard());
    }

    /**
     * @depends testCanICreateACadastroConverterInstance
     */
    public function testCanIGetAValidDelimiterConfig(CadastroConverter $converter)
    {
        $this->assertIsString($converter->getDelimiter());
    }

    /**
     * @depends testCanICreateACadastroConverterInstance
     */
    public function testICanAccessAllGettersValuesForCadastro(CadastroConverter $converter)
    {
        $this->assertIsInt($converter->getCutTop());
        $this->assertIsInt($converter->getCutBottom());
        $this->assertIsInt($converter->getConcatEvery());
        $this->assertIsArray($converter->getFieldsToImplode());
    }

    public function testCanIGetTheConverterConfigForCadastro(): void
    {
        $this->assertIsArray(Converter::getConfig(
            $this->fileToConvert, 
            $this->rawFile
        ));
    }

    public function testCanIGetAParsedLinesForCadastroUniondata(): void
    {
        


    }
}