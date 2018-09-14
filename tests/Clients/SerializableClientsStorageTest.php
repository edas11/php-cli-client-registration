<?php
declare(strict_types=1);

use Edvardas\Clients\SerializableClientsStorage;
use PHPUnit\Framework\TestCase;

final class SerializableClientsStorageTest extends TestCase {
    public function testCanBeCreated(): void
    {
        new SerializableClientsStorage();
        $this->expectOutputString("created Edvardas\Clients\SerializableClientsStorage stub \n");
    }
}