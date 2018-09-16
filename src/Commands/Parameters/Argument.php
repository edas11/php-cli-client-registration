<?php

namespace Edvardas\Commands\Parameters;

class Argument{

    private $value;

    public function __construct(string $value) {
        $this->value = $value;
    }

    public function getValue(): string {
        return $this->value;
    }
}