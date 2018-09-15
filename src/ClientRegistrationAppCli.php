<?php
namespace Edvardas;

use Edvardas\Commands\CommandParser;
use Edvardas\Output\CliOutput;

class ClientRegistrationAppCli{
    
    public function __construct(){
    }

    public function executeCommand(): void {
        try {
            $cmd = (new CommandParser())->getCommand();
            $cmd->execute();
        } catch (\Exception $e) {
            CliOutput::get()->printError($e->getMessage());
        }
    }
    
}