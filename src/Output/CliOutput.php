<?php
namespace Edvardas\Output;

use Edvardas\Commands\Messages\HelpMessage;
use Edvardas\Clients\Clients;

class CliOutput{
    
    private static $outputInstance;

    private function __construct(){
    }
    
    public static function get() {
        if (is_null(self::$outputInstance)) self::$outputInstance = new CliOutput();
        return self::$outputInstance;
    }

    public function printHelpMessage(string $info, array $helpMsg): void {
        echo "$info\n\n";
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
        echo 'Success: '.$successMsg."\n";
    }

    public function printError(string $errorMsg): void {
        echo 'Error: '.$errorMsg."\n";
    }

    public function printWarning(string $warningMsg): void {
        echo 'Warning: '.$warningMsg."\n";
    }

    public function printClients(Clients $clients): void {
        echo "Firstname\tLastname\tEmail\tPhonenumber1\tPhonenumber2\tComment\n";
        foreach($clients as $client) {
            echo $client->getFirstname()."\t".
                $client->getLastname()."\t".
                $client->getEmail()."\t".
                $client->getPhonenumber1()."\t".
                $client->getPhonenumber2()."\t".
                $client->getComment()."\n";
        }
    }
}