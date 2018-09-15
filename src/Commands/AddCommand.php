<?php
namespace Edvardas\Commands;

use Edvardas\Clients\ClientInputData;
use Edvardas\Clients\Client;
use Edvardas\Commands\Command;
use Edvardas\Clients\SerializableClientsStorage;
use Edvardas\Output\CliOutput;

class AddCommand implements Command{
    
    private $clientInput;
    private $storage;
    private $output;

    public function __construct(ClientInputData $input, SerializableClientsStorage $storage, CliOutput $out){
        $this->clientInput = $input;
        $this->storage = $storage;
        $this->output = $out;
    }

    public function execute(){
        $clientToAdd = new Client($this->clientInput->firstname, $this->clientInput->lastname,
            $this->clientInput->email, $this->clientInput->phonenumber1, $this->clientInput->phonenumber2,
            $this->clientInput->comment);
        $this->storage->add($clientToAdd);
        $this->output->printSuccess("Client ".$clientToAdd->getEmail()." added");
    }
}