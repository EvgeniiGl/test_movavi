<?php

namespace Course\Test;

use Course\Swap;
use PHPUnit\Framework\TestCase;

class SwapTest extends TestCase
{

    public function testSetDateChangesDate()
    {
        $swap = new Swap();
        $swap->setDate('2015-01-01');
        $this->assertEquals(1420070400, $swap->getDate());
    }

    public function test__constructSetDateCorrectIfDateParamNull()
    {
        $swap = new Swap();
        $this->assertEquals(time(), $swap->getDate());
    }

    public function test__constructSetDateCorrectIfDateParamPassed()
    {
        $swap = new Swap('2015-01-01');
        $this->assertEquals(1420070400, $swap->getDate());
    }
}
