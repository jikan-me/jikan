<?php

namespace Jikan\Request\User;

use Jikan\Request\RequestInterface;

/**
 * Class UsernameByIdRequest
 *
 * @package Jikan\Request
 */
class UsernameByIdRequest implements RequestInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * UsernameByIdRequest constructor.
     *
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return sprintf('https://myanimelist.net/comments.php?id=%d', $this->id);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}
