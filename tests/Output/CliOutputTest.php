<?php
declare(strict_types=1);

use Edvardas\Output\CliOutput;
use PHPUnit\Framework\TestCase;

final class CliOutputTest extends TestCase {
    public function testCanBeCreated(): void
    {
        new CliOutput();
        $this->expectOutputString("created Edvardas\Output\CliOutput stub \n");
    }
}