<?php
namespace Edvardas\Clients;

use Edvardas\Clients\Client;
use Edvardas\Clients\Clients;
use Edvardas\Validation\Validator;
use Edvardas\Filesystem\NullFile;
use Edvardas\Filesystem\BinaryDataFile;
use TheSeer\Tokenizer\Exception;

class SerializableClientsStorage{

    private $serialize;
    private $clients = [];
    private $file;
    
    public function __construct(bool $serialize = true){
        $this->serialize = $serialize;
        if ($serialize) {
            $this->file = new BinaryDataFile('./client-reg-data');
            $this->initialiseClients($this->file->read());
        } else {
            $this->file = new NullFile();
        }
    }

    public function add(Client $newClient): void {
        $this->throwExceptionIfEmailNotUnique($newClient);
        array_push($this->clients, $newClient);
        $this->file->save(serialize($this->clients));
    }

    public function delete(string $email): void {
        $res = array_filter($this->clients, function(Client $client) use ($email) {
            return $client->getEmail() !== $email;
        });
        if (count($this->clients)===count($res)) throw new \OutOfBoundsException("Client with email $email not found");
        $this->clients = array_values($res);
        $this->file->save(serialize($this->clients));
    }

    public function replace(string $email, Client $newClient): void {
        $res = $this->findClient($email);
        if (count($res)===0) throw new \OutOfBoundsException("Client with email $email not found");
        if ($email!==$newClient->getEmail()) $this->throwExceptionIfEmailNotUnique($newClient);
        $this->clients = array_replace($this->clients, [array_keys($res)[0] => $newClient]);
        $this->file->save(serialize($this->clients));
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
            if ($client->getEmail()===$testClient->getEmail()) throw new \DomainException('Client with email '.$testClient->getEmail().' already exists');
        }
    }
    private function initialiseClients(string $data): void{
        if (strlen($data)===0) return;
        else {
            $received = unserialize($data);
            if (!is_array($received)) throw new \Exception('Bad data in data file');
            foreach($received as $element) {
                if (!($element instanceof Client)) throw new \Exception('Bad data in data file');
            }
            $this->clients = $received;
        }
    }
}