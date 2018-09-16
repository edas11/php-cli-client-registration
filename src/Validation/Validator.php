<?php
namespace Edvardas\Validation;

class Validator{
    
    public static function email(string $value): bool{
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }
    public static function required(string $value): bool{
        return !empty($value);
    }
    public static function phonenumber(string $value): bool{
        if (!preg_match('/^[0-9+ ()-]*$/', $value)) return false;
        $testVal = str_replace('+', '', $value);
        $testVal = str_replace(' ', '', $testVal);
        $testVal = str_replace('-', '', $testVal);
        $testVal = str_replace('(', '', $testVal);
        $testVal = str_replace(')', '', $testVal);
        return strlen($testVal)<20;
    }
    /*public static function unique($value, array $otherValues): bool{
        foreach($otherValues as $otherValue) {
            if ($otherValue === $value) return false;
        }
        return true;
    }*/
    
}