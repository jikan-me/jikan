<?php

namespace Jikan\Request\Reviews;

use Jikan\Helper\Constants;
use Jikan\Request\RequestInterface;

/**
 * Class TopReviewsRequest
 *
 * @package Jikan\Request\Top
 */
class RecentReviewsRequest implements RequestInterface
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
     * TopReviewsRequest constructor.
     *
     * @param int  $page
     * @param string $type
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(string $type = Constants::RECENT_REVIEW_BEST_VOTED, int $page = 1)
    {
        $this->page = $page;

        if (null !== $type) {
            if (!\in_array(
                $type,
                [
                    Constants::RECENT_REVIEW_BEST_VOTED,
                    Constants::RECENT_REVIEW_ANIME,
                    Constants::RECENT_REVIEW_MANGA,
                ],
                true
            )
            ) {
                throw new \InvalidArgumentException(sprintf('Review type %s is not valid', $type));
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
        if ($this->type === Constants::RECENT_REVIEW_BEST_VOTED) {
            return 'https://myanimelist.net/reviews.php?'.http_build_query(
                [
                        'p' => $this->page,
                        'st'  => $this->type,
                    ]
            );
        }

        return 'https://myanimelist.net/reviews.php?'.http_build_query(
            [
                    'p' => $this->page,
                    't'  => $this->type,
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

    /**
     * @return array
     */
    public function getCookies(): array
    {
        return [];
    }
}
