<?php
namespace Edvardas\Clients;

use Edvardas\Clients\Client;

class Clients implements \IteratorAggregate{

    private $clients;

    public function __construct(array $clients) {
        $this->clients = $clients; 
    }

    public function getIterator() {
        return new \ArrayIterator($this->clients);
    }
}