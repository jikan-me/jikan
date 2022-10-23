<?php

namespace Jikan\Request\User;

use Jikan\Request\RequestInterface;

/**
 * Class UserClubsRequest
 *
 * @package Jikan\Request
 */
class UserClubsRequest implements RequestInterface
{
    /**
     * @var string
     */
    private $username;

    /**
     * UserClubsRequest constructor.
     * @param string $username
     */
    public function __construct(string $username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return sprintf('https://myanimelist.net/profile/%s/clubs', $this->username);
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }
}
