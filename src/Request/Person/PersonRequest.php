<?php

namespace Jikan\Request\Person;

use Jikan\Request\RequestInterface;

/**
 * Class Person
 *
 * @package Jikan\Request
 */
class PersonRequest implements RequestInterface
{
    /**
     * @var int
     */
    private $personId;

    /**
     * Person constructor.
     *
     * @param int $personId
     */
    public function __construct($personId)
    {
        $this->personId = $personId;
    }

    /**
     * Get the path to request
     *
     * @return string
     */
    public function getPath(): string
    {
        return sprintf('https://myanimelist.net/people/%s', $this->personId);
    }
}
