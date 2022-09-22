<?php

namespace Jikan\Request\User;

use Jikan\Request\RequestInterface;

/**
 * Class UserRecommendationsRequest
 *
 * @package Jikan\Request
 */
class UserRecommendationsRequest implements RequestInterface
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
     * UserReviewsRequest constructor.
     * @param string $username
     * @param int|null $page
     */
    public function __construct(string $username, ?int $page = 1)
    {
        $this->username = $username;
        $this->page = $page;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return sprintf('https://myanimelist.net/profile/%s/recommendations?p=%d', $this->username, $this->page);
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
    public function getPage(): ?int
    {
        return $this->page;
    }

    /**
     * @return array
     */
    public function getCookies(): array
    {
        return [];
    }
}
