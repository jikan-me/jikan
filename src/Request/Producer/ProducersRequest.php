<?php

namespace Jikan\Request\Producer;

use Jikan\Request\RequestInterface;

/**
 * Class ProducersRequest
 *
 * @package Jikan\Request
 */
class ProducersRequest implements RequestInterface
{
    /**
     * ProducersRequest constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return sprintf('https://myanimelist.net/anime/producer');
    }
}
