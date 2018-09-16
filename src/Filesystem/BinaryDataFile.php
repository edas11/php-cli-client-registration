<?php

namespace Edvardas\Filesystem;

use Edvardas\Filesystem\File;

class BinaryDataFile extends File {

    private $fileName;

    public function __construct(string $fileName) {
        $this->fileName = $fileName;
        if (!is_file($fileName)) {
            $dataFile = fopen($fileName, "wb");
            if ($dataFile===false) throw new \Exception("Data file $fileName not found and cannot be created");
            $closed = fclose($dataFile);
            if (!$closed) throw new \Exception("Cannot close data file $fileName");
        }
    }

    public function read(): string {
        if (is_readable($this->fileName)) {
            $contents = file_get_contents($this->fileName);
            if ($contents===false) throw new \Exception("Cannot read data file $this->fileName");
            return $contents;
        } else {
            throw new \Exception("Cannot read data file $this->fileName");
        }
    }

    public function save(string $content): void {
        if (is_writable($this->fileName)) {
            $dataFile = fopen($this->fileName, "wb");
            if ($dataFile===false) throw new \Exception("Cannot write to data file $fileName");
            $written = fwrite($dataFile, $content);
            if ($written<=0) throw new \Exception("Nothing writen to data file $fileName");
        } else {
            throw new \Exception("Cannot write to data file $fileName");
        }
    }

}