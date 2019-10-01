<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class ConverterTest extends TestCase
{
    public function testCanICreateAParentConverterInstance(): void
    {
        // Pass same file as input and raw options
        $file = 'tests/assets/empty.xls';
        $this->assertInstanceOf(
            Standardizer\Converter::class,
            (new Standardizer\Converter($file, $file))
        );        
    }
}