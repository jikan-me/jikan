<?php

namespace Jikan\Model\Recommendations;

use Jikan\Parser;

/**
 * Class RecentRecommendations
 *
 * @package Jikan\Model\Reviews\RecentReviews
 */
class RecentRecommendations
{

    /**
     * @var boolean
     */
    private $hasNextPage = false;

    /**
     * @var array
     */
    private $recommendations;

    /**
     * @param Parser\Reviews\RecentReviewsParser $parser
     *
     * @return RecentRecommendations
     * @throws \Exception
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public static function fromParser(Parser\Recommendations\RecentRecommendationsParser $parser): self
    {
        $instance = new self();

        $instance->recommendations = $parser->getRecentRecommendations();
        $instance->hasNextPage = $parser->hasNextPage();

        return $instance;
    }

    /**
     * @return array
     */
    public function getRecommendations(): array
    {
        return $this->recommendations;
    }

    /**
     * @return bool
     */
    public function hasNextPage(): bool
    {
        return $this->hasNextPage;
    }
}
