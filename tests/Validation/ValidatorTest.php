<?php
declare(strict_types=1);

use Edvardas\Validation\Validator;
use PHPUnit\Framework\TestCase;

final class ValidatorTest extends TestCase {
    public function testCanBeCreated(): void
    {
        new Validator();
        $this->expectOutputString("created Edvardas\Validation\Validator stub \n");
    }
}