<?php

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /** A basic test example. */
    #[Test]
    public function thatTrueIsTrue(): void
    {
        $this->assertTrue(true);
    }
}
