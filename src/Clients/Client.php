<?php
namespace Edvardas\Clients;

class Client{
    
    public function __construct() {
        echo "created ".self::class." stub \n";
    }

    public function test(): string {
        return 'test';
    }
    
}