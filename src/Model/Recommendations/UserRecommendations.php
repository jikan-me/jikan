<?php

namespace Jikan\Model\Recommendations;

use Jikan\Model\Common\Collection\Pagination;
use Jikan\Model\Common\Collection\Results;
use Jikan\Parser;

/**
 * Class UserRecommendations
 *
 * @package Jikan\Model\UserReviewsParser\Reviews
 */
class UserRecommendations extends Results implements Pagination
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
     * @param Parser\Recommendations\UserRecommendationsParser $parser
     * @return static
     */
    public static function fromParser(Parser\Recommendations\UserRecommendationsParser $parser): self
    {
        $instance = new self();

        $instance->results = $parser->getUserRecommendations();
        $instance->lastVisiblePage = $parser->getLastPage();
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
