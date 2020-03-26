<?php

namespace Course;

/**
 * Interface HttpClient
 */
interface HttpClient
{
    /**
     * @param array $requests
     * @return array
     */
    public function asyncRequest(array $requests):array;
}
