<?php
declare(strict_types=1);

use Edvardas\Clients\Client;
use PHPUnit\Framework\TestCase;

final class ClientTest extends TestCase {
    public function testCanBeCreated(): void
    {
        new Client();
        $this->expectOutputString("created Edvardas\Clients\Client stub \n");
    }
}