<?php

namespace Jikan\Request\Magazine;

use Jikan\Request\RequestInterface;

/**
 * Class ProducersRequest
 *
 * @package Jikan\Request
 */
class MagazinesRequest extends \Jikan\Request\Magazine\MagazineRequest implements RequestInterface
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
