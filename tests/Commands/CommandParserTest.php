<?php
declare(strict_types=1);

use Edvardas\Commands\CommandParser;
use PHPUnit\Framework\TestCase;

final class CommandParserTest extends TestCase {
    public function testCanBeCreated(): void
    {
        $obj = new CommandParser();
        $this->assertInstanceOf(CommandParser::class, $obj);
    }
}