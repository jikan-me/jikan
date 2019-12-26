<?php

namespace Jikan\Request\Magazine;

use Jikan\Request\RequestInterface;

/**
 * Class ProducersRequest
 *
 * @package Jikan\Request
 */
class MagazinesRequest implements RequestInterface
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
        return sprintf('https://myanimelist.net/manga/magazine');
    }
}
