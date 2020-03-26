<?php


namespace Course;


use Exception;
use GuzzleHttp\Psr7;
use Throwable;

/**
 * Class ApiException
 * @package Course
 */
class ApiException extends Exception
{
    /**
     * @param $message
     * @param $code
     * @param Throwable $t
     */

    private $throwable;

    /**
     * ApiException constructor.
     * @param $message
     * @param $code
     * @param Throwable $t
     */
    public function __construct($message, $code, Throwable $t=null)
    {
        $this->throwable = $t;
        parent::__construct($message, $code, $t);
        $this->handler();
    }

    public function handler()
    {
        echo $this->getMessage() . ". ";
        echo "Код: " . $this->getCode() . ". ";
        if ($this->throwable->hasResponse()) {
            echo Psr7\str($this->throwable->getResponse());
        }
    }


}
