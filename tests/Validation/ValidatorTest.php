<?php
declare(strict_types=1);

use Edvardas\Validation\Validator;
use PHPUnit\Framework\TestCase;

final class ValidatorTest extends TestCase {

    public function testCanValidateEmail(): void {
        $this->assertFalse(Validator::email('abcd'));
        $this->assertTrue(Validator::email('ed@gmail.com'));
    }
    public function testCanRequiredValue(): void {
        $this->assertFalse(Validator::required(''));
        $this->assertFalse(Validator::required(""));
        $this->assertTrue(Validator::required('abcd'));
    }
    public function testCanValidatePhonenumber(): void {
        $this->assertFalse(Validator::phonenumber('abcd'));
        $this->assertFalse(Validator::phonenumber('88888888888888888888888888888888888'));
        $this->assertTrue(Validator::phonenumber('+370 555 66666'));
        $this->assertTrue(Validator::phonenumber(''));
        $this->assertTrue(Validator::phonenumber('37055566666'));
        $this->assertTrue(Validator::phonenumber('(8-654) 44444'));
        $this->assertTrue(Validator::phonenumber('865444444'));
    }
    public function testCanValidateThatValueIsUnique(): void {
        /*$values = [1, 2, 3, 4];
        $this->assertFalse(Validator::unique(1, $values));
        $this->assertTrue(Validator::unique(5, $values));*/
    }

}