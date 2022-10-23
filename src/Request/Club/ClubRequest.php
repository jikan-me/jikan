<?php

namespace Jikan\Request\Club;

use Jikan\Request\RequestInterface;

/**
 * Class ClubRequest
 *
 * @package Jikan\Request\Club
 */
class ClubRequest implements RequestInterface
{
    /**
     * @var int
     */
    private $clubId;

    /**
     * ClubRequest constructor.
     *
     * @param int $clubId
     */
    public function __construct(int $clubId)
    {
        $this->clubId = $clubId;
    }

    /**
     * Get the path to request
     *
     * @return string
     */
    public function getPath(): string
    {
        return sprintf(
            'https://myanimelist.net/clubs.php?cid=%s',
            $this->clubId
        );
    }

    /**
     * @return int
     */
    public function getClubId(): int
    {
        return $this->clubId;
    }
}
