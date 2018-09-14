<?php
declare(strict_types=1);

use Edvardas\Output\CliOutput;
use Edvardas\Commands\Messages\HelpMessage;
use PHPUnit\Framework\TestCase;

final class CliOutputTest extends TestCase {
    public function testCanBeCreated(): void
    {
        $obj = new CliOutput();
        $this->assertInstanceOf(CliOutput::class, $obj);
    }
    public function testCanPrintHelpMessagesFromMessageArray(): void {
        $out = new CliOutput();
        $msg1 = new HelpMessage("a", "b", "c");
        $msg2 = new HelpMessage("d", "e", "f");
        $msg3 = new HelpMessage("g", "h", "i");
        $msg = [$msg1, $msg2, $msg3];
        $out->printHelpMessage($msg);

        $expectedStr =  "a\nDescription: b\nUsage: c\n\n".
                        "d\nDescription: e\nUsage: f\n\n".
                        "g\nDescription: h\nUsage: i\n\n";
        $this->expectOutputString($expectedStr);
    }
    public function testDoesntPrintHelpMessageIfMessagesArrayEmpty(): void {
        $out = new CliOutput();
        $out->printHelpMessage([]);
        $this->expectOutputString("");
    }
    public function testThrowsErrorIfHelpMessagesArrayHasNonMessageObjects(): void {
        $this->expectException(InvalidArgumentException::class);
        $out = new CliOutput();
        $out->printHelpMessage([null, $out]);
    }
}