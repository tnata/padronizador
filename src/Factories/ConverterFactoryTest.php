<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class ConverterFactoryTest extends TestCase
{
    public function testCanICreateACadastroConverterFromFactory(): void
    {
        $this->assertInstanceOf(
            Standardizer\Converters\CadastroConverter::class,
            Standardizer\Factories\ConverterFactory::create('cadastro')
        );
    }
}