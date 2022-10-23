<?php

namespace Jikan\Request\Club;

use Jikan\Request\RequestInterface;

/**
 * Class UserListRequest
 *
 * @package Jikan\Request\Club
 */
class UserListRequest implements RequestInterface
{
    /**
     * @var int
     */
    private $clubId;

    /**
     * @var int
     */
    private $page;

    /**
     * UserList constructor.
     *
     * @param int $clubId
     * @param int $page
     */
    public function __construct(int $clubId, int $page = 1)
    {
        $this->clubId = $clubId;
        $this->page = ($page - 1) * 36;
    }

    /**
     * Get the path to request
     *
     * @return string
     */
    public function getPath(): string
    {
        return sprintf(
            'https://myanimelist.net/clubs.php?action=view&t=members&id=%s&show=%s',
            $this->clubId,
            $this->page
        );
    }

    /**
     * @return int
     */
    public function getClubId(): int
    {
        return $this->clubId;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }
}
