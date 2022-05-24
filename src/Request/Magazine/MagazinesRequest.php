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
     * @return string
     */
    public function getPath(): string
    {
        return sprintf('https://myanimelist.net/manga/magazine');
    }
}
