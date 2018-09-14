<?php
namespace Edvardas\Commands\Messages;

class HelpMessage {

    private $name = '';
    private $description = '';
    private $usage = '';
    
    public function __construct(string $name, string $description, string $usage) {
        $this->name = $name;
        $this->description = $description;
        $this->usage = $usage;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getUsage(): string {
        return $this->usage;
    }
    
}