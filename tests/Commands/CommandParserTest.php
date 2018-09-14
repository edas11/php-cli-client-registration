<?php
declare(strict_types=1);

use Edvardas\Commands\CommandParser;
use PHPUnit\Framework\TestCase;

final class CommandParserTest extends TestCase {
    public function testCanBeCreated(): void
    {
        new CommandParser();
        $this->expectOutputString("created Edvardas\Commands\CommandParser stub \n");
    }
}