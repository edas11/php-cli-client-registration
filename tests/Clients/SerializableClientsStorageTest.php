<?php
declare(strict_types=1);

use Edvardas\Clients\SerializableClientsStorage;
use Edvardas\Clients\Client;
use Edvardas\Clients\Clients;
use PHPUnit\Framework\TestCase;

final class SerializableClientsStorageTest extends TestCase {

    protected $data;
    protected $storage;

    protected function setUp() {
        $data = [new Client('a', 'a', 'ed@g.g', '', '', ''),
                            new Client('a', 'a', 'de@g.g', '', '', ''),
                            new Client('a', 'a', 'tt@g.g', '', '', '')];
        $this->storage = new SerializableClientsStorage(false);
        $this->data = $data;
        $this->storage->add($data[0]);
        $this->storage->add($data[1]);
        $this->storage->add($data[2]);
    }

    public function testCanAddandGetClients(): void {
        $this->assertSame($this->data[0], $this->storage->get('ed@g.g'));
        $this->assertSame($this->data[1], $this->storage->get('de@g.g'));
        $this->assertSame($this->data[2], $this->storage->get('tt@g.g'));
    }
    public function testThrowsExceptionIfAddingClientWithNotUniqueEmail(): void {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage("Email ed@g.g already exists");
        $this->storage->add($this->data[0]);
        $this->storage->add($this->data[0]);
    }
    public function testCanGetAllClients(): void{
        $this->assertEquals(new Clients($this->data), $this->storage->getAll());
    }
    public function testThrowsExceptionIfAskedForNonexistentClient(): void {
        $this->expectException(\OutOfBoundsException::class);
        $this->expectExceptionMessage('Client with email abc not found');
        $this->storage->get('abc');
    }
    public function testCanDeleteClient(): void{
        $this->storage->delete('de@g.g');
        $this->assertEquals(new Clients([$this->data[0], $this->data[2]]), $this->storage->getAll());
        $this->expectException(\OutOfBoundsException::class);
        $this->expectExceptionMessage('Client with email de@g.g not found');
        $this->storage->get('de@g.g');
    }
    public function testThrowsExceptionIfAskedToDeleteNonexistentClient(): void{
        $this->expectException(\OutOfBoundsException::class);
        $this->expectExceptionMessage('Client with email abc not found');
        $this->storage->delete('abc');
    }
    public function testCanReplaceClient(): void{
        $new = new Client('a', 'a', 'new@g.g', '', '', '');
        $this->storage->replace('de@g.g', $new);
        $this->assertEquals(new Clients([$this->data[0], $new, $this->data[2]]), $this->storage->getAll());
        $this->expectException(\OutOfBoundsException::class);
        $this->expectExceptionMessage('Client with email de@g.g not found');
        $this->storage->get('de@g.g');
    }
    public function testThrowsExceptionIfAskedToReplaceNonexistentClient(): void{
        $this->expectException(\OutOfBoundsException::class);
        $this->expectExceptionMessage('Client with email abc not found');
        $this->storage->replace('abc', new Client('a', 'a', 'new@g.g', '', '', ''));
    }
    public function testThrowsExceptionIfReplacingClientWithOneHavingNotUniqueEmail(): void {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage("Email tt@g.g already exists");
        $new = new Client('a', 'a', 'tt@g.g', '', '', '');
        $this->storage->replace('de@g.g', $new);
    }
}