<?php
namespace Edvardas\Output;

use Edvardas\Commands\Messages\HelpMessage;

class CliOutput{
    
    public function __construct(){
    }
    
    public function printHelpMessage(array $helpMsg): void {
        foreach($helpMsg as $msg) {
            if (!$msg instanceof HelpMessage) { 
                throw new \InvalidArgumentException("printHelpMessage expects array of HelpMessage objects");
            };
            echo $msg->getName();
            echo "\nDescription: ";
            echo $msg->getDescription();
            echo "\nUsage: ";
            echo $msg->getUsage();
            echo "\n\n";
        }
    }
    public function printSuccess(string $successMsg): void {

    }
    public function printClients(array $clients): void {

    }
}