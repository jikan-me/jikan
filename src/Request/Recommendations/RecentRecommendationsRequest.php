<?php

namespace Jikan\Request\Recommendations;

use Jikan\Helper\Constants;
use Jikan\Request\RequestInterface;

/**
 * Class RecentRecommendationsRequest
 *
 * @package Jikan\Request\Top
 */
class RecentRecommendationsRequest implements RequestInterface
{
    /**
     * @var int
     */
    private $page;

    /**
     * @var string
     */
    private $type;

    /**
     * RecentRecommendationsRequest constructor.
     *
     * @param int  $page
     * @param string $type
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(string $type = Constants::RECENT_RECOMMENDATION_ANIME, int $page = 1)
    {
        $this->page = $page;

        if (null !== $type) {
            if (!\in_array(
                $type,
                [
                    Constants::RECENT_RECOMMENDATION_ANIME,
                    Constants::RECENT_RECOMMENDATION_MANGA
                ],
                true
            )
            ) {
                throw new \InvalidArgumentException(sprintf('Recommendation type %s is not valid', $type));
            }

            $this->type = $type;
        }
    }

    /**
     * Get the path to request
     *
     * @return string
     */
    public function getPath(): string
    {
        return 'https://myanimelist.net/recommendations.php?'.http_build_query(
            [
                    's' => 'recentrecs',
                    't'  => $this->type,
                    'show' => ($this->page !== 1) ? 100 * ($this->page - 1) : null
                ]
        );
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
