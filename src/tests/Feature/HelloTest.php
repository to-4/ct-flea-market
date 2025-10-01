<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase; // ← LaravelのではなくPHPUnitのTestCase


class HelloTest extends TestCase
{
    #[Test]
    public function hello_world(): void
    {
        $this->assertTrue(true); // Assert that true is true

        $arr = [];
        $this->assertEmpty($arr); // Assert that the array is empty

        $txt = "Hello World";
        $this->assertEquals('Hello World', $txt); // Assert that the text equals "Hello World"

        $n = random_int(0, 100);
        $this->assertLessThan(100, $n); // Assert that the random number is less than 100
    }
}
