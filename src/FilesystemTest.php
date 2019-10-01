<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class FilesystemTest extends TestCase
{
    public function testCanReadInputFolderRecursive(): void
    {
        $this->assertIsArray(
            Standardizer\Filesystem::scanAllDir('input')
        );
    }

    public function testCanCountTextFileLines()
    {
        $this->assertEquals(
            10,
            Standardizer\Filesystem::countLines('tests/assets/test.txt')
        );
    }

    public function testCanIGetTextFileLinesArray()
    {
        $this->assertIsArray(
            Standardizer\Filesystem::getLines('tests/assets/test.txt')
        );

        $this->expectException(\Exception::class);
        Standardizer\Filesystem::getLines('invalid');
    }
}