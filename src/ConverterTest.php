<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class ConverterTest extends TestCase
{
    public function testCanICreateAParentConverterInstance(): void
    {
        $this->assertInstanceOf(
            Standardizer\Converter::class,
            (new Standardizer\Converter('tests/assets/empty.xls'))
        );        
    }
}