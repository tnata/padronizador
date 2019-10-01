<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class CadastroConverterTest extends TestCase
{
    public function testCanICreateACadastroConverterInstance()
    {
        $converter = Standardizer\Factories\ConverterFactory::create('cadastro.xls');
        $this->assertInstanceOf(
            Standardizer\Converters\CadastroConverter::class,
            $converter
        );
        return $converter;
    }

    /**
     * @depends testCanICreateACadastroConverterInstance
     */
    public function testICanGetAValidStandardConfig($converter)
    {
        $this->assertArrayHasKey('fields', $converter->getStandard());
    }
}