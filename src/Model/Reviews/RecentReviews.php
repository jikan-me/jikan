<?php

namespace Jikan\Model\Reviews;

use Jikan\Parser;

/**
 * Class RecentReviews
 *
 * @package Jikan\Model\ReviewsParser\RecentReviews
 */
class RecentReviews
{

    /**
     * @var boolean
     */
    private $hasNextPage = false;

    /**
     * @var array
     */
    private $reviews;

    /**
     * @param Parser\Reviews\RecentReviewsParser $parser
     *
     * @return RecentReviews
     * @throws \Exception
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public static function fromParser(Parser\Reviews\RecentReviewsParser $parser): self
    {
        $instance = new self();

        $instance->reviews = $parser->getRecentReviews();
        $instance->hasNextPage = $parser->hasNextPage();

        return $instance;
    }

    /**
     * @return array
     */
    public function getReviews(): array
    {
        return $this->reviews;
    }

    /**
     * @return bool
     */
    public function hasNextPage(): bool
    {
        return $this->hasNextPage;
    }
}
