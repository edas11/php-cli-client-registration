<?php
declare(strict_types=1);

use Edvardas\Output\CliOutput;
use Edvardas\Commands\Messages\HelpMessage;
use PHPUnit\Framework\TestCase;
use Edvardas\Clients\Client;
use Edvardas\Clients\Clients;

final class CliOutputTest extends TestCase {
    public function testCanPrintHelpMessagesFromMessageArray(): void {
        $out = CliOutput::get();
        $info = 'smth';
        $msg1 = new HelpMessage("a", "b", "c");
        $msg2 = new HelpMessage("d", "e", "f");
        $msg3 = new HelpMessage("g", "h", "i");
        $msg = [$msg1, $msg2, $msg3];
        $out->printHelpMessage($info, $msg);

        $expectedStr =  "$info\n\n".
                        "a\nDescription: b\nUsage: c\n\n".
                        "d\nDescription: e\nUsage: f\n\n".
                        "g\nDescription: h\nUsage: i\n\n";
        $this->expectOutputString($expectedStr);
    }
    public function testCanPrintClientsInfo(): void {
        $clients = new Clients([new Client('a', 'b', 'g@g.g', '8', '8', 'c'),
            new Client('a', 'b', 'gg@g.g', '8', '8', 'c')]);
        $out = CliOutput::get();
        $out->printClients($clients);
        $this->expectOutputString("Firstname\tLastname\tEmail\tPhonenumber1\tPhonenumber2\tComment\n".
                                "a\tb\tg@g.g\t8\t8\tc\n".
                                "a\tb\tgg@g.g\t8\t8\tc\n");
    }
    public function testCanPrintSuccesMessage(): void {
        $out = CliOutput::get();
        $out->printSuccess('test');
        $this->expectOutputString("Success: test\n");
    }
    public function testCanPrintWarningMessage(): void {
        $out = CliOutput::get();
        $out->printWarning('test');
        $this->expectOutputString("Warning: test\n");
    }
    public function testCanPrintErrorMessage(): void {
        $out = CliOutput::get();
        $out->printError('test');
        $this->expectOutputString("Error: test\n");
    }
    public function testDoesntPrintHelpMessageIfMessagesArrayEmpty(): void {
        $out = CliOutput::get();
        $out->printHelpMessage('', []);
        $this->expectOutputString("\n\n");
    }
    public function testThrowsErrorIfHelpMessagesArrayHasNonMessageObjects(): void {
        $this->expectException(InvalidArgumentException::class);
        $out = CliOutput::get();
        $out->printHelpMessage('', [null, $out]);
    }
}