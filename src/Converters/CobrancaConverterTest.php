<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class CobrancaConverterTest extends TestCase
{
    public function testCanICreateACobrancaConverterInstance()
    {
        $converter = new Standardizer\Converters\CobrancaConverter('cobranca.xls');
        $this->assertInstanceOf(
            Standardizer\Converters\CobrancaConverter::class,
            $converter
        );
        return $converter;
    }

    /**
     * @depends testCanICreateACobrancaConverterInstance
     */
    public function testICanGetAValidStandardConfig($converter)
    {
        $this->assertArrayHasKey('fields', $converter->getStandard());
    }
}