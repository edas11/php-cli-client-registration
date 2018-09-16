<?php
namespace Edvardas\Clients;

use Edvardas\Clients\Client;

class ClientBuilder{
    
    private $firstname;
    private $lastname;
    private $email;
    private $phonenumber1;
    private $phonenumber2;
    private $comment;

    public function build(): Client{
        if ($this->notValidForBuild()) throw new \UnexpectedValueException('Trying to build Client with null fields');
        return new Client($this->firstname, $this->lastname, $this->email, 
            $this->phonenumber1, $this->phonenumber2, $this->comment);
    }

    public function setFirstname(string $firstname): ClientBuilder{
        if (is_null($this->firstname)) $this->firstname = $firstname;
        return $this;
    }
    public function setLastname(string $lastname): ClientBuilder{
        if (is_null($this->lastname)) $this->lastname = $lastname;
        return $this;
    }
    public function setEmail(string $email): ClientBuilder{
        if (is_null($this->email)) $this->email = $email;
        return $this;
    }
    public function setPhonenumber1(string $phonenumber1): ClientBuilder{
        if (is_null($this->phonenumber1)) $this->phonenumber1 = $phonenumber1;
        return $this;
    }
    public function setPhonenumber2(string $phonenumber2): ClientBuilder{
        if (is_null($this->phonenumber2)) $this->phonenumber2 = $phonenumber2;
        return $this;
    }
    public function setComment(string $comment): ClientBuilder{
        if (is_null($this->comment))  $this->comment = $comment;
        return $this;
    }

    private function notValidForBuild() {
        return is_null($this->firstname) || is_null($this->lastname) || is_null($this->email) ||
            is_null($this->phonenumber1) || is_null($this->phonenumber2) || is_null($this->comment); 
    }
    
}