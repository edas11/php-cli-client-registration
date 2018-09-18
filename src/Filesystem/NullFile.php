<?php

namespace Edvardas\Filesystem;

use Edvardas\Filesystem\File;

class NullFile extends File {

    public function __construct() {
    }

    public function read(): string {
    }

    public function save(string $content): void {
    }

}