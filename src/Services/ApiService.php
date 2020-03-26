<?php

namespace Course\Services;

use Psr\Http\Message\ResponseInterface;

/**
 * Interface ApiService
 * @package Course\Services
 */
interface ApiService
{
    /**
     * @param int $date
     * @return array
     */
    public function getQuotes(int $date): array;
}
