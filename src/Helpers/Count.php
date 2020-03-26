<?php

namespace Course\Helpers;

/**
 * Class Count
 * @package Course\Helpers
 */
class Count
{
    /**
     * @param int $number1 Value in rubles*10000
     * @param int $number2 Value in rubles*10000
     * @return float|int Average value in rubles
     */
    public static function average(int $number1, int $number2)
    {
        return (($number1 + $number2) / 2) / 10000;
    }

    /**
     * @param string $val monetary value in rubles
     * @return int Value in rubles*10000
     */
    public static function conversion($val):int
    {
        return (int)(str_replace(",", ".", $val) * 10000);
    }
}
