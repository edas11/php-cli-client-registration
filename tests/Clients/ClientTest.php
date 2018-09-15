<?php
declare(strict_types=1);

use Edvardas\Clients\Client;
use PHPUnit\Framework\TestCase;

final class ClientTest extends TestCase {
    public function testThrowsExceptionIfEmailEmpty(): void {
        $this->expectException(\DomainException::class);
        new Client('a', 'a', '', '', '', 'a');
    }
    public function testThrowsExceptionIfEmailNotValid(): void {
        $this->expectException(\DomainException::class);
        new Client('a', 'a', 'a', '', '', 'a');
    }
    public function testThrowsExceptionIfPhone1NotValid(): void {
        $this->expectException(\DomainException::class);
        new Client('a', 'a', 'a@a.com', 'qq', '', 'a');
    }
    public function testThrowsExceptionIfPhone2NotValid(): void {
        $this->expectException(\DomainException::class);
        new Client('a', 'a', 'a@a.com', '', 'qq', 'a');
    }
}