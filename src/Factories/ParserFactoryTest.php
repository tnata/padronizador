<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use Standardizer\Factories\ExporterFactory;

final class ParserFactoryTest extends TestCase
{
    private $uniondataCadastroExporter;
    private $uniondataDefaultCobrancaExporter;
    private $uniondataAcordoCobrancaExporter;

    protected function setUp() : void
    {
        $this->uniondataCadastroExporter = ExporterFactory::create(
            'tests/assets/uniondata/cadastro.xls'
        );
        $this->uniondataDefaultCobrancaExporter = ExporterFactory::create(
            'tests/assets/uniondata/inadimplencia.xls'
        ); 
        $this->uniondataAcordoCobrancaExporter = ExporterFactory::create(
            'tests/assets/uniondata/acordos.xls'
        );
    }
    
    public function testCanICreateAUniondataDefaultCadastroParserInstance(): void
    {
        $this->assertInstanceOf(
            Standardizer\Parsers\UniondataParser::class,
            Standardizer\Factories\ParserFactory::create($this->uniondataCadastroExporter)
        );
    }

    public function testCanICreateAUniondataDefaultCobrancaParserInstance(): void
    {
        $this->assertInstanceOf(
            Standardizer\Parsers\UniondataParser::class,
            Standardizer\Factories\ParserFactory::create($this->uniondataDefaultCobrancaExporter)
        );
    }

    public function testCanICreateAUniondataAcordoCobrancaParserInstance(): void
    {
        $this->assertInstanceOf(
            Standardizer\Parsers\UniondataParser::class,
            Standardizer\Factories\ParserFactory::create($this->uniondataAcordoCobrancaExporter)
        );
    }
}