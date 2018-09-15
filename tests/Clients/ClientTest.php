<?php
declare(strict_types=1);

use Edvardas\Clients\Client;
use PHPUnit\Framework\TestCase;

final class ClientTest extends TestCase {
    public function testThrowsExceptionIfEmailEmpty(): void {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Email is required');
        new Client('a', 'a', '', '', '', 'a');
    }
    public function testThrowsExceptionIfEmailNotValid(): void {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Email is not valid');
        new Client('a', 'a', 'a', '', '', 'a');
    }
    public function testThrowsExceptionIfPhone1NotValid(): void {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Phonenumber1 is not valid. Must be less than 20 digits, can only contain digits, spaces, +, -, ( and )');
        new Client('a', 'a', 'a@a.com', 'qq', '', 'a');
    }
    public function testThrowsExceptionIfPhone2NotValid(): void {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Phonenumber2 is not valid. Must be less than 20 digits, can only contain digits, spaces, +, -, ( and )');
        new Client('a', 'a', 'a@a.com', '', 'qq', 'a');
    }
}