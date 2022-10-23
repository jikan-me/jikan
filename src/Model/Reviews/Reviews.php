<?php

namespace Jikan\Model\Reviews;

use Jikan\Model\Common\Collection\Pagination;
use Jikan\Model\Common\Collection\Results;
use Jikan\Model\Recommendations\UserRecommendations;
use Jikan\Parser;

/**
 * Class Reviews
 *
 * @package Jikan\Model\UserReviewsParser\Reviews
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
     * @param Parser\Reviews\ReviewsParser $parser
     *
     * @return Reviews
     * @throws \Exception
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public static function fromParser(Parser\Reviews\ReviewsParser $parser): self
    {
        $instance = new self();

        $instance->results = $parser->getReviews();
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
