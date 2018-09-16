<?php
declare(strict_types=1);

use Edvardas\Clients\Client;
use Edvardas\Clients\ClientBuilder;
use PHPUnit\Framework\TestCase;

final class ClientBuilderTest extends TestCase {
    public function testCanCreateClient(): void {
        $builder = (new ClientBuilder())->setFirstname('a')->setLastname('b')->setEmail('g@g.g')
            ->setPhonenumber1('8')->setPhonenumber2('8')->setComment('c')->setFirstname('aa');
        $client = new Client('a', 'b', 'g@g.g', '8', '8', 'c');
        $this->assertEquals($client, $builder->build());
    }
    public function testThrowsExceptionIfTryingBuildClientWithNullField(): void {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Trying to build Client with null fields');
        $builder = (new ClientBuilder())->setFirstname('a')->setLastname('b')
            ->setPhonenumber1('8')->setPhonenumber2('8');
        $builder->build();
    }
}