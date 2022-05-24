<?php

namespace Jikan\Request\Top;

use Jikan\Helper\Constants;
use Jikan\Request\RequestInterface;

/**
 * Class TopAnimeRequest
 *
 * @package Jikan\Request\Top
 */
class TopAnimeRequest implements RequestInterface
{
    /**
     * @var int
     */
    private $page;

    /**
     * @var string|null
     */
    private $type;

    /**
     * TopAnimeRequest constructor.
     *
     * @param int  $page
     * @param null $type
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(int $page = 1, $type = null)
    {
        $this->page = $page;

        if (null !== $type) {
            if (!\in_array(
                $type,
                [
                    Constants::TOP_AIRING,
                    Constants::TOP_UPCOMING,
                    Constants::TOP_TV,
                    Constants::TOP_MOVIE,
                    Constants::TOP_OVA,
                    Constants::TOP_SPECIAL,
                    Constants::TOP_BY_POPULARITY,
                    Constants::TOP_BY_FAVORITES,
                    Constants::TOP_ONA
                ],
                true
            )
            ) {
                throw new \InvalidArgumentException(sprintf('Type %s is not valid', $type));
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
        return 'https://myanimelist.net/topanime.php?'.http_build_query(
            [
                    'limit' => 50 * ($this->page - 1),
                    'type'  => $this->type,
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
     * @return string|null
     */
    public function getType()
    {
        return $this->type;
    }
}
