<?php

namespace Jikan\Model\User;

use Jikan\Model\Common\Collection\Pagination;
use Jikan\Model\Common\Collection\Results;
use Jikan\Model\Reviews\RecentReviews;
use Jikan\Parser\Reviews\RecentReviewsParser;
use Jikan\Parser\User\ReviewsParser;

/**
 * Class ReviewsParser
 *
 * @package Jikan\Model
 */
class Reviews extends Results implements Pagination
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
     * @param ReviewsParser $parser
     * @return Reviews
     */
    public static function fromParser(ReviewsParser $parser): Reviews
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
