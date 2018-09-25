<?php

namespace Jikan\Request\User;

use Jikan\Request\RequestInterface;

/**
 * Class UserAnimeListRequest
 *
 * @package Jikan\Request
 */
class UserAnimeListRequest implements RequestInterface
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
     * @var int
     */
    private $status;

    /**
     * UserAnimeListRequest constructor.
     *
     * @param string $username
     * @param int $page
     */
    public function __construct(string $username, int $page = 1, int $status = 7)
    {
        $this->username = $username;
        $this->page = ($page - 1) * 300;
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        $query = '?'.http_build_query([
            'offset' => $this->page,
            'status' => $this->status
        ]);
        return sprintf('https://myanimelist.net/animelist/%s/load.json%s', $this->username, $query);
    }
}
