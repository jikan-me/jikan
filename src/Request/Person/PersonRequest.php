<?php

namespace Jikan\Request\Person;

use Jikan\Request\RequestInterface;

/**
 * Class PersonRequest
 *
 * @package Jikan\Request
 */
class PersonRequest implements RequestInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * PersonRequest constructor.
     *
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * Get the path to request
     *
     * @return string
     */
    public function getPath(): string
    {
        return sprintf('https://myanimelist.net/people/%s', $this->id);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}
