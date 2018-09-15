<?php
namespace Edvardas\Clients;


class ClientInputData{
    
    //These can be null meaning no input
    public $firstname = null;
    public $lastname = null;
    public $email = null;
    public $phonenumber1 = null;
    public $phonenumber2 = null;
    public $comment = null;

    private function __construct() {
    }

    public static function createEmpty(): ClientInputData {
        return new ClientInputData();
    }
    public static function create(string $firstname, string $lastname, string $email, 
            string $phonenumber1, string $phonenumber2, string $comment): ClientInputData {
        $input = new ClientInputData();
        $input->firstname = $firstname;
        $input->lastname = $lastname;
        $input->email = $email;
        $input->phonenumber1 = $phonenumber1;
        $input->phonenumber2 = $phonenumber2;
        $input->comment = $comment;
        return $input;
    }
}