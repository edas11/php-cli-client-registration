<?php
namespace Edvardas;

use Edvardas\Commands\CommandParser;
use Edvardas\Output\CliOutput;
use Edvardas\Clients\SerializableClientsStorage;

class ClientRegistrationAppCli{
    
    public function __construct(){
    }

    public function executeCommand(): void {
        try {
            $cmd = (new CommandParser(new SerializableClientsStorage()))->getCommand();
            $cmd->execute();
        } catch (\Exception $e) {
            CliOutput::get()->printError($e->getMessage());
        }
    }
    
}