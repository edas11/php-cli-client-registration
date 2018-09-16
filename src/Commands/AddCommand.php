<?php
namespace Edvardas\Commands;

use Edvardas\Clients\ClientBuilder;
use Edvardas\Clients\Client;
use Edvardas\Commands\Command;
use Edvardas\Clients\SerializableClientsStorage;
use Edvardas\Output\CliOutput;

class AddCommand implements Command{
    
    private $clientBuilder;
    private $storage;
    private $output;

    public function __construct(ClientBuilder $builder, SerializableClientsStorage $storage, CliOutput $out){
        $this->clientBuilder = $builder;
        $this->storage = $storage;
        $this->output = $out;
    }

    public function execute(){
        $clientToAdd = $this->clientBuilder->build();
        $this->storage->add($clientToAdd);
        $this->output->printSuccess("Client ".$clientToAdd->getEmail()." added");
    }
}