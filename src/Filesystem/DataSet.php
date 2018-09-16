<?php

namespace Edvardas\Filesystem;

class DataSet {

    private $data;

    public function __construct(array $data) {
        foreach($data as $value) {
            if (!is_string($value)) throw new \Exception("Trying to create DataSet of non string values");
        }
        $this->data = $data;
    }
    public function count(): int {
        return count($this->data);
    }
    public function getAt(int $i): string {
        return $this->data[$i];
    }
}