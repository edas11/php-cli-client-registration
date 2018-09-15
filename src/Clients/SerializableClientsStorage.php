<?php
namespace Edvardas\Clients;

use Edvardas\Clients\Client;

class SerializableClientsStorage{

    private $inMemory;
    
    public function __construct(bool $inMemory = false){
        $this->inMemory = $inMemory;
    }

    public function add(Client $client): bool {
        return false;
    }

    public function delete(string $email): bool {
        return false;
    }

    public function replace(string $email): bool {
        return false;
    }
    
    public function get(string $email): Client {
        return null;
    }

    public function getAll(): array {
        return null;
    }
}