<?php

namespace Jikan\Model\User;

use Jikan\Model\Common\Collection\Pagination;
use Jikan\Model\Reviews\RecentReviews;
use Jikan\Parser\Reviews\RecentReviewsParser;
use Jikan\Parser\User\ReviewsParser;

/**
 * Class ReviewsParser
 *
 * @package Jikan\Model
 */
class Reviews implements Pagination
{
    /**
     * @var int|null
     */
    private $lastVisiblePage;
    /**
     * @var bool
     */
    private $hasNextPage = false;

    /**
     * @var array
     */
    private $reviews = [];

    /**
     * @param ReviewsParser $parser
     * @return Reviews
     */
    public static function fromParser(ReviewsParser $parser): Reviews
    {
        $instance = new self();
        $instance->reviews = $parser->getReviews();
        $instance->lastVisiblePage = $parser->getLastVisiblePage();
        $instance->hasNextPage = $parser->hasNextPage();

        return $instance;
    }

    /**
     * @return int|null
     */
    public function getLastVisiblePage(): ?int
    {
        // TODO: Implement getLastVisiblePage() method.
    }

    /**
     * @return bool
     */
    public function hasNextPage(): bool
    {
        // TODO: Implement hasNextPage() method.
    }
}
