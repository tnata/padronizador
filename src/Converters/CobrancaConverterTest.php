<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use Standardizer\Converters\CobrancaConverter;

final class CobrancaConverterTest extends TestCase
{
    private $fileToConvert = 'cobranca.xls';
    private $testFile = 'tests/assets/test.txt';
    private $rawFile;

    protected function setUp() : void
    {
        $this->rawFile = $this->testFile;
    }

    public function testCanICreateACobrancaConverterInstance()
    {
        $converter = new CobrancaConverter(
            $this->fileToConvert,
            $this->rawFile
        );
        $this->assertInstanceOf(
            CobrancaConverter::class,
            $converter
        );
        return $converter;
    }

    /**
     * @depends testCanICreateACobrancaConverterInstance
     */
    public function testICanGetAValidStandardConfig(CobrancaConverter $converter)
    {
        $this->assertArrayHasKey('fields', $converter->getStandard());
    }

    /**
     * @depends testCanICreateACobrancaConverterInstance
     */
    public function testCanIGetAValidDelimiterConfig(CobrancaConverter $converter)
    {
        $this->assertIsString($converter->getDelimiter());
    }
    
    /**
     * @depends testCanICreateACobrancaConverterInstance
     */
    public function testCanISetAllConverterModes(CobrancaConverter $converter)
    {
        foreach(config('converters')->get('available') as $type) {
            if ($type['name'] == 'cobranca') {
                foreach($type['subtypes'] as $subtype => $values) {
                    $converter->setMode($subtype);
                    $this->assertEquals(
                        $subtype,
                        $converter->getMode()
                    );
                }
            }
        }
    }

    /**
     * @depends testCanICreateACobrancaConverterInstance
     */
    public function testICantSetAnInvalidMode(CobrancaConverter $converter)
    {
        $this->expectException(\Exception::class);
        $converter->setMode('invalid');
    }

    /**
     * @depends testCanICreateACobrancaConverterInstance
     */
    public function testCanIAccessAllGettersValuesForCobranca(CobrancaConverter $converter)
    {
        $this->assertIsInt($converter->getCutTop());
        $this->assertIsInt($converter->getCutBottom());
        $this->assertIsInt($converter->getConcatEvery());
        $this->assertIsArray($converter->getFieldsToImplode());
    }

    public function testCanIGetTheConverterConfigForCobranca(): void
    {
        $this->assertIsArray(Standardizer\Converter::getConfig(
            $this->fileToConvert, 
            $this->rawFile
        ));
    }
}