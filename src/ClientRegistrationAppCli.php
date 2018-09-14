<?php
namespace Edvardas;

use Edvardas\Commands\CommandParser;
use Edvardas\Output\CliOutput;

class ClientRegistrationAppCli{
    
    public function __construct(){
    }

    public function executeCommand(): void {
        $cmd = (new CommandParser())->getCommand();
        $cmd->execute();
    }
    
}