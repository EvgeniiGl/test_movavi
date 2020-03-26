<?php

namespace Course\Test\Helpers;

use Course\Helpers\Count;
use PHPUnit\Framework\TestCase;

/**
 * Class CountTest
 * @package Course\Test\Helpers
 */
class CountTest extends TestCase
{

    /**
     * @dataProvider conversionProvider
     */
    public function testConversionReturnCorrectValue($val, $res)
    {
        $c = new Count();
        $this->assertSame($c->conversion($val), $res);
        $this->assertIsInt($c->conversion($val));
    }

    /**
     * @dataProvider numberProvider
     */
    public function testAverageReturnCorrectValue($num1, $num2, $res)
    {
        $c = new Count();
        $this->assertSame($c->average($num1, $num2), $res);
        $this->assertIsFloat($c->average($num1, $num2));
    }

    public function numberProvider()
    {
        return array(
            [15, 15, 0.0015],
            [160000, 150000, 15.5000],
            [1000, 500, 0.0750],
        );
    }

    public function conversionProvider()
    {
        return array(
            ['15', 150000],
            ['34.4544', 344544],
            ['34,4544', 344544],
            [34.4544, 344544],
        );
    }

}
