<?php
namespace Edvardas\Commands;

use Edvardas\Commands\Command;
use Edvardas\Clients\SerializableClientsStorage;
use Edvardas\Output\CliOutput;

class ListCommand implements Command{

    private $storage;
    private $output;

    public function __construct(SerializableClientsStorage $storage, CliOutput $out){
        $this->storage = $storage;
        $this->output = $out;
    }

    public function execute() {
        $this->output->printClients($this->storage->getAll());
    }

}