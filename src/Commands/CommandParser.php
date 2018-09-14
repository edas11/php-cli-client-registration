<?php
namespace Edvardas\Commands;

use Edvardas\Commands\HelpCommand;
use Edvardas\Output\CliOutput;

class CommandParser{
    
    public function __construct(){
    }

    public function getCommand(): Command {
        return new HelpCommand(new CliOutput());
    }
    
}