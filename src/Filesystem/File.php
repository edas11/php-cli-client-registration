<?php

namespace Edvardas\Filesystem;

abstract class File {

    abstract public function read(): string;
    abstract public function save(string $content);

}