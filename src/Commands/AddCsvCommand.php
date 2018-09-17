<?php
namespace Edvardas\Commands;

use Edvardas\Commands\Command;
use Edvardas\Filesystem\CsvFile;
use Edvardas\Clients\SerializableClientsStorage;
use Edvardas\Clients\Client;
use Edvardas\Output\CliOutput;

class AddCsvCommand implements Command{

    private $csvFilename;
    private $clientBuilder;
    private $storage;
    private $output;

    public function __construct(string $csvFilename, SerializableClientsStorage $storage, CliOutput $out){
        $this->csvFilename = $csvFilename;
        $this->storage = $storage;
        $this->output = $out;
    }

    public function execute() {
        $csvFile = new CsvFile($this->csvFilename);
        while ($csvFile->hasMore()) {
            $csvData = $csvFile->readLine();
            if ($csvData->count()!=6) throw new \Exception('All csv records must have 6 fields');
            $clientToAdd = new Client($csvData->getAt(0), $csvData->getAt(1), $csvData->getAt(2)
                , $csvData->getAt(3), $csvData->getAt(4), $csvData->getAt(5));
            try{
                $this->storage->add($clientToAdd);
            } catch(\DomainException $e) {
                $this->output->printWarning("Client exists, skipping");
            }
        }
        $this->output->printSuccess('Clients added');
    }

}