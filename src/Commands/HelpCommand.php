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
            new HelpMessage('add', 'Adds client.', 'cust-reg.php add firstname lastname email phonenumber1 phonenumber2 comment'),
            new HelpMessage('edit', 'Edits client.', 'cust-reg.php edit [--firstname=new-val] [--lastname=new-val] [--email=new-val] [--phonenumber1=new-val] [--phonenumber2=new-val] [--comment=new-val] client-email'),
            new HelpMessage('delete', 'Deletes client.', 'cust-reg.php delete client-email'),
            new HelpMessage('list', 'Lists customers.', 'cust-reg.php list'),
            new HelpMessage('help', 'Prints this help message.', 'cust-reg.php list')
        ];
        $this->out->printHelpMessage($helpMsg);
    }
    
}