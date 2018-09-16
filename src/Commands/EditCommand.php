<?php
namespace Edvardas\Commands;

use Edvardas\Clients\ClientBuilder;
use Edvardas\Clients\Client;
use Edvardas\Commands\Command;
use Edvardas\Clients\SerializableClientsStorage;
use Edvardas\Output\CliOutput;

class EditCommand implements Command{
    
    private $clientEmail;
    private $clientBuilder;
    private $storage;
    private $output;

    public function __construct(string $clientEmail, ClientBuilder $builder, SerializableClientsStorage $storage,
            CliOutput $out){
        $this->clientBuilder = $builder;
        $this->storage = $storage;
        $this->output = $out;
        $this->clientEmail = $clientEmail;
    }

    public function execute() {
        $client = $this->storage->get($this->clientEmail);
        $this->clientBuilder->setFirstname($client->getFirstname())->setLastname($client->getLastname())
            ->setEmail($client->getEmail())->setPhonenumber1($client->getPhonenumber1())
            ->setPhonenumber2($client->getPhonenumber2())->setComment($client->getComment());
        $newClient = $this->clientBuilder->build();
        $this->storage->replace($this->clientEmail, $newClient);

        $outputMsg = "Client ".$client->getEmail()." modified";
        if ($client->getEmail() !== $newClient->getEmail()) {
            $outputMsg = $outputMsg.". New email ".$newClient->getEmail();
        }
        $this->output->printSuccess($outputMsg);
    }
}