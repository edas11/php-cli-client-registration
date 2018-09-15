<?php
namespace Edvardas\Commands;

use Edvardas\Commands\Command;
use Edvardas\Clients\SerializableClientsStorage;
use Edvardas\Output\CliOutput;

class DeleteCommand implements Command {
    
    private $email;
    private $storage;
    private $output;

    public function __construct(string $email, SerializableClientsStorage $storage, CliOutput $out){
        $this->email = $email;
        $this->storage = $storage;
        $this->output = $out;
    }

    public function execute() {
        $this->storage->delete($this->email);
        $this->output->printSuccess("Client $this->email deleted");
    }

}