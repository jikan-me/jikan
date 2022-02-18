<?php

namespace Jikan\Request\Top;

use Jikan\Helper\Constants;
use Jikan\Request\RequestInterface;

/**
 * Class TopMangaRequest
 *
 * @package Jikan\Request\Top
 */
class TopMangaRequest implements RequestInterface
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
                    Constants::TOP_MANGA,
                    Constants::TOP_NOVEL,
                    Constants::TOP_ONE_SHOT,
                    Constants::TOP_DOUJINSHI,
                    Constants::TOP_MANHWA,
                    Constants::TOP_MANHUA,
                    Constants::TOP_BY_POPULARITY,
                    Constants::TOP_BY_FAVORITES,
                    Constants::TOP_LIGHTNOVELS
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
        return 'https://myanimelist.net/topmanga.php?'.http_build_query(
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
