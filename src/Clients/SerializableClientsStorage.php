<?php
namespace Edvardas\Clients;

use Edvardas\Clients\Client;
use Edvardas\Clients\Clients;
use Edvardas\Validation\Validator;

class SerializableClientsStorage{

    private $serialize;
    private $clients = [];
    
    public function __construct(bool $serialize = true){
        $this->serialize = $serialize;
    }

    public function add(Client $newClient): bool {
        $this->throwExceptionIfEmailNotUnique($newClient);
        array_push($this->clients, $newClient);
        return true;
    }

    public function delete(string $email): bool {
        $res = array_filter($this->clients, function(Client $client) use ($email) {
            return $client->getEmail() !== $email;
        });
        if (count($this->clients)===count($res)) throw new \OutOfBoundsException("Client with email $email not found");
        $this->clients = array_values($res);
        return true;
    }

    public function replace(string $email, Client $newClient): bool {
        $res = $this->findClient($email);
        if (count($res)===0) throw new \OutOfBoundsException("Client with email $email not found");
        $this->throwExceptionIfEmailNotUnique($newClient);
        $this->clients = array_replace($this->clients, [array_keys($res)[0] => $newClient]);
        return true;
    }
    
    public function get(string $email): Client {
        $res = $this->findClient($email);
        $res = array_values($res);
        if (count($res)===0) throw new \OutOfBoundsException("Client with email $email not found");
        return $res[0];
    }

    public function getAll(): Clients {
        return new Clients($this->clients);
    }

    private function findClient($email): array {
        return array_filter($this->clients, function(Client $client) use ($email) {
            return $client->getEmail() === $email;
        });
    }
    private function throwExceptionIfEmailNotUnique(Client $testClient) {
        foreach($this->clients as $client) {
            if ($client->getEmail()===$testClient->getEmail()) throw new \DomainException('Email '.$testClient->getEmail().' already exists');
        }
    }
}