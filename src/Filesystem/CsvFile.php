<?php

namespace Edvardas\Filesystem;

use Edvardas\Filesystem\DataSet;

class CsvFile {

    private $lineData;
    private $lineTakenForEofCheckFlag = false;
    private $csvFile;

    public function __construct($fileName) {
        $this->csvFile = fopen($fileName, 'rb');
        if ($this->csvFile===false) throw new \Exception("Cannot open file $fileName");
    }
    public function readLine(): DataSet {
        $data = fgetcsv($this->csvFile);
        if ($data[0]===null) throw new \Exception("Blank line in csv file detected, please remove it");
        if ($data===false) throw new \Exception("Cannot read line of file $fileName");
        return new DataSet($data);
    }
    public function hasMore(): bool {
        $hasMore = fgetc($this->csvFile)!==false;
        fseek ($this->csvFile, -1, SEEK_CUR);
        return $hasMore;
    }
}