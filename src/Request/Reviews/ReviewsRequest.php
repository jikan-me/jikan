<?php

namespace Jikan\Request\Reviews;

use Jikan\Helper\Constants;
use Jikan\Request\RequestInterface;

/**
 * Class ReviewsRequest
 *
 * @package Jikan\Request\Reviews;
 */
class ReviewsRequest implements RequestInterface
{
    /**
     * @var string
     */
    private string $type;

    /**
     * @var int
     */
    private int $page;

    /**
     * @var string
     */
    private string $sort;

    /**
     * @var bool
     */
    private bool $spoilers;

    /**
     * @var bool
     */
    private bool $preliminary;


    public function __construct($type = Constants::ANIME, int $page = 1, string $sort = Constants::REVIEWS_SORT_MOST_VOTED, bool $spoilers = true, bool $preliminary = true)
    {
        $this->page = $page;
        $this->sort = $sort;
        $this->spoilers = $spoilers;
        $this->preliminary = $preliminary;

        if (null !== $type) {
            if (!\in_array(
                $type,
                [
                    Constants::ANIME,
                    Constants::MANGA,
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
        return 'https://myanimelist.net/reviews.php?'.http_build_query(
            [
                'p' => $this->page,
                't'  => $this->type,
                'spoiler' => $this->spoilers ? 'on' : 'off',
                'preliminary' => $this->preliminary ? 'on' : 'off',
                'sort' => $this->sort,
            ]
        );
    }
}
