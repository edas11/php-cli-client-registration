<?php
namespace Edvardas\Commands;

use Edvardas\Commands\Command;
use Edvardas\Output\CliOutput;
use Edvardas\Commands\Messages\HelpMessage;

class HelpCommand implements Command{

    private $out;
    
    public function __construct(CliOutput $out) {
        $this->out = $out;
    }

    public function execute() {
        $helpMsg = [
            new HelpMessage('add', 'Adds one client.', 'add "firstname" "lastname" "email" "phonenumber1" "phonenumber2" "comment"'),
            new HelpMessage('add --csv', 'Adds clients from csv.', 'add --csv="path/to/csv"'),
            new HelpMessage('edit', 'Edits client.', 'edit [--firstname="new value"] [--lastname="new value"] [--email="new value"] [--phonenumber1="new value"] [--phonenumber2="new value"] [--comment="new value"] "client email"'),
            new HelpMessage('delete', 'Deletes a client.', 'delete "client email"'),
            new HelpMessage('list', 'Lists clients.', 'list'),
            new HelpMessage('help', 'Prints this help message.', 'help'),
            new HelpMessage('end', 'Deletes data file.', 'end')
        ];
        $this->out->printHelpMessage('cust-reg.php is a simple clien registration program. It expects one of the following commands:', $helpMsg);
    }
    
}