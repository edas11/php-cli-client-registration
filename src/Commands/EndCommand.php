<?php
namespace Edvardas\Commands;

use Edvardas\Commands\Command;
use Edvardas\Output\CliOutput;

class EndCommand implements Command{

    private $storage;
    private $output;

    public function __construct(CliOutput $out){
        $this->output = $out;
    }

    public function execute() {
        $status = unlink('./client-reg-data');
        if (!$status) throw new \Exeception('Couldnt delete data file');
        $this->output->printSuccess('Data file deleted');
    }

}