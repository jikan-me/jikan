<?php

namespace Jikan\Model\User\Reviews;

use Jikan\Model\Common\Collection\Pagination;
use Jikan\Model\Common\Collection\Results;
use Jikan\Parser\User\Reviews\UserReviewsParser;

/**
 * Class UserReviewsParser
 *
 * @package Jikan\Model
 */
class UserReviews extends Results implements Pagination
{
    /**
     * @var bool
     */
    private $hasNextPage = false;

    /**
     * @var int
     */
    private $lastVisiblePage = 1;

    /**
     * @param UserReviewsParser $parser
     * @return UserReviews
     */
    public static function fromParser(UserReviewsParser $parser): UserReviews
    {
        $instance = new self();
        $instance->results = $parser->getReviews();
        $instance->lastVisiblePage = $parser->getLastVisiblePage();
        $instance->hasNextPage = $parser->hasNextPage();

        return $instance;
    }

    /**
     * @return static
     */
    public static function mock() : self
    {
        return new self();
    }

    /**
     * @return array
     */
    public function getResults(): array
    {
        return $this->results;
    }

    /**
     * @return bool
     */
    public function hasNextPage(): bool
    {
        return $this->hasNextPage;
    }

    /**
     * @return int
     */
    public function getLastVisiblePage(): int
    {
        return $this->lastVisiblePage;
    }
}
