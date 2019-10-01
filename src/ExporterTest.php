<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use Standardizer\Exporter;
use Standardizer\Filesystem;

final class ExporterTest extends TestCase
{
    private $emptyFileToExport = 'tests/assets/empty.xls'; 
    private $expectedRawOutput = 'raw/empty.csv';

    public function testCanICreateAExporterFromClass() : Exporter
    {
        $exporter = new Exporter(
            $this->emptyFileToExport, 
            Filesystem::getInfo($this->emptyFileToExport)
        );

        $this->assertInstanceOf(Exporter::class, $exporter);

        return $exporter;
    }

    /**
     * @depends testCanICreateAExporterFromClass
     */
    public function testCanIExportAXlsToCsv(Exporter $exporter) : void
    {
        $this->assertStringContainsString(
            $this->expectedRawOutput,
            $exporter->run()
        );

        $this->assertFileExists($this->expectedRawOutput);

        // Erase generated raw output file
        unlink($this->expectedRawOutput);
    }
}