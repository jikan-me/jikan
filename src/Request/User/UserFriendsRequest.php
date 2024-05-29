<?php

namespace Jikan\Request\User;

use Jikan\Request\RequestInterface;

/**
 * Class UserFriends
 *
 * @package Jikan\Request
 */
class UserFriendsRequest implements RequestInterface
{
    /**
     * @var string
     */
    private $username;
    /**
     * @var int
     */
    private $page;

    /**
     * UserProfileRequest constructor.
     *
     * @param string $username
     * @param int    $page     starts at 1
     */
    public function __construct(string $username, int $page = 1)
    {
        $this->username = $username;
        $this->page = $page;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        $query = '';
        if ($this->page) {
            $query = '?'.http_build_query(['p' => $this->page]);
        }

        return sprintf('https://myanimelist.net/profile/%s/friends%s', $this->username, $query);
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }
}
