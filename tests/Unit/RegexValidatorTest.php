<?php

namespace Tests\Unit;

use App\Service\RegexValidator;
use PHPUnit\Framework\TestCase;

class RegexValidatorTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testMatch()
    {
        $validator = new RegexValidator('/TEST/');
        $this->assertTrue($validator->isMatch("TEST.com"));
        $this->assertFalse($validator->isMatch("xxx.com"));
    }
}
