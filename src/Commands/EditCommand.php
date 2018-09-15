<?php
namespace Edvardas\Commands;

use Edvardas\Clients\ClientInputData;
use Edvardas\Clients\Client;
use Edvardas\Commands\Command;
use Edvardas\Clients\SerializableClientsStorage;
use Edvardas\Output\CliOutput;

class EditCommand implements Command{
    
    private $clientEmail;
    private $clientInput;
    private $storage;
    private $output;

    public function __construct(string $clientEmail, ClientInputData $input, SerializableClientsStorage $storage,
            CliOutput $out){
        $this->clientInput = $input;
        $this->storage = $storage;
        $this->output = $out;
        $this->clientEmail = $clientEmail;
    }

    public function execute() {
        $client = $this->storage->get($this->clientEmail);
        $newFirstname = $client->getFirstname();
        $newLastname = $client->getLastname();
        $newEmail = $client->getEmail();
        $newPhonenumber1 = $client->getPhonenumber1();
        $newPhonenumber2 = $client->getPhonenumber2();
        $newComment = $client->getComment();
        if (!is_null($this->clientInput->firstname)) $newFirstname=$this->clientInput->firstname;
        if (!is_null($this->clientInput->lastname)) $newLastname=$this->clientInput->lastname;
        if (!is_null($this->clientInput->email)) $newEmail=$this->clientInput->email;
        if (!is_null($this->clientInput->phonenumber1)) $newPhonenumber1=$this->clientInput->phonenumber1;
        if (!is_null($this->clientInput->phonenumber2)) $newPhonenumber2=$this->clientInput->phonenumber2;
        if (!is_null($this->clientInput->comment)) $newComment=$this->clientInput->comment;
        $newClient = new Client($newFirstname, $newLastname, $newEmail, $newPhonenumber1, $newPhonenumber2, $newComment);
        $this->storage->replace($this->clientEmail, $newClient);

        $outputMsg = "Client ".$client->getEmail()." modified";
        if ($client->getEmail() !== $newEmail) $outputMsg = $outputMsg.". New email $newEmail";
        $this->output->printSuccess($outputMsg);
    }
}