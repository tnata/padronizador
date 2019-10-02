<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use Standardizer\Parser;

final class ParserTest extends TestCase
{
    public function testICantCreateAParentParserInstance(): void
    {
        $this->expectException(\Exception::class);
        new Parser();
    }
}