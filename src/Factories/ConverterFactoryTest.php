<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class ConverterFactoryTest extends TestCase
{
    private $testFile = 'tests/assets/test.txt';
    private $rawFile;

    protected function setUp() : void
    {
        $this->rawFile = $this->testFile;
    }

    public function testCanICreateACadastroConverterFromFactory(): void
    {
        $this->assertInstanceOf(
            Standardizer\Converters\CadastroConverter::class,
            Standardizer\Factories\ConverterFactory::create('cadastro.xls', $this->rawFile)
        );
    }

    public function testCanICreateACobrancaConverterFromFactory(): void
    {
        $this->assertInstanceOf(
            Standardizer\Converters\CobrancaConverter::class,
            Standardizer\Factories\ConverterFactory::create('cobranca.xls', $this->rawFile)
        );
    }

    public function testIGetExceptionWhenRawFileNotExists(): void
    {
        $this->expectException(\Exception::class);
        Standardizer\Factories\ConverterFactory::create('cobranca.xls', 'invalid');
    }
}