<?php

namespace Course;

use Course\Helpers\Count;
use Course\Services\ApiCbr;
use Course\Services\ApiRbc;

/**
 * Swap provides access to the average exchange rate EUR/RUB and USD/RUB
 */
class Swap
{
    /**
     * @var int Date timestamp, settable once per new instance
     */
    protected $date;
    /**
     * @var array configs
     */
    private $config;

    /**
     * @param $date string A date string, if not passed it will be assigned today.
     * @throws \Exception Throw exception if date not valid.
     */
    public function __construct($date = null)
    {
        if ($date === null) {
            $this->date = time();
        } else {
            $this->date = self::setTimestamp($date);
        }
        $this->config = require 'config.php';
    }

    /**
     * Sets the date for all future new instances
     * @param $date string A date string.
     * @throws \Exception Throw exception if date not valid.
     */
    public function setDate(string $date)
    {
        $this->date = $this->setTimestamp($date);
    }

    /**
     * @return int
     */
    public function getDate(): int
    {
        return $this->date;
    }

    /**
     * @param $date string A date string.
     * @return false|int
     * @throws \Exception Throw exception if date not valid.
     */
    private function setTimestamp($date)
    {
        if (($timestamp = strtotime($date)) === false) {
            throw new \Exception("Дата ($date) недопустима");
        }
        return $timestamp;
    }

    /**
     * Get the average value of currency quotes.
     *
     * @return array Return array the average exchange rate EUR/RUB and USD/RUB [code=>value]
     */
    public function quote()
    {
        $client = new GuzzleClient();
        $crb = new ApiCbr($client);
        $quotesCbr = $crb->getQuotes($this->getDate());
        $rbc = new ApiRbc($client);
        $quotesRbc = $rbc->getQuotes($this->getDate());
        $quotes = [];
        foreach ($this->config['codes'] as $code) {
            $quotes[$code] = Count::average($quotesCbr[$code], $quotesRbc[$code]);
        }
        return $quotes;
    }
}




