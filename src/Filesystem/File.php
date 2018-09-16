<?php

namespace Edvardas\Filesystem;

abstract class File {

    abstract public function __construct(string $fileName);
    abstract public function read(): string;
    abstract public function save(string $content);

}