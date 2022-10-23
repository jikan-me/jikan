<?php

namespace Jikan\Request\User;

use Jikan\Request\RequestInterface;

/**
 * Class RecentlyOnlineUsersRequest
 *
 * @package Jikan\Request
 */
class RecentlyOnlineUsersRequest implements RequestInterface
{
    /**
     * @return string
     */
    public function getPath(): string
    {
        return 'https://myanimelist.net/users.php';
    }
}
