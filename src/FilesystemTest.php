<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class FilesystemTest extends TestCase
{
    public function testCanReadInputFolderRecursive(): void
    {
        $folders = Standardizer\Filesystem::scanAllDir('input');

        $this->assertIsArray($folders);
    }

    public function testCanReadTextFile()
    {
        
    }
}