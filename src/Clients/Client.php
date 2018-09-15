<?php
namespace Edvardas\Clients;

use Edvardas\Validation\Validator;


class Client{
    
    private $firstname;
    private $lastname;
    private $email;
    private $phonenumber1;
    private $phonenumber2;
    private $comment;

    public function __construct(string $firstname, string $lastname, string $email, 
            string $phonenumber1, string $phonenumber2, string $comment) {
        if (!Validator::required($email)) throw new \DomainException('Email is required');
        if (!Validator::email($email)) throw new \DomainException('Email is not valid');
        if (!Validator::phonenumber($phonenumber1)) throw new \DomainException('Phonenumber1 is not valid. Must be less than 20 digits, can only contain digits, spaces, +, -, ( and )');
        if (!Validator::phonenumber($phonenumber2)) throw new \DomainException('Phonenumber2 is not valid. Must be less than 20 digits, can only contain digits, spaces, +, -, ( and )');
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->phonenumber1 = $phonenumber1;
        $this->phonenumber2 = $phonenumber2;
        $this->comment = $comment;
    }

    public function getFirstname(): string{
        return $this->firstname;
    }
    public function getLastname(): string{
        return $this->lastname;
    }
    public function getEmail(): string{
        return $this->email;
    }
    public function getPhonenumber1(): string{
        return $this->phonenumber1;
    }
    public function getPhonenumber2(): string{
        return $this->phonenumber2;
    }
    public function getComment(): string{
        return $this->comment;
    }
    
}