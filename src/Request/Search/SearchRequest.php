<?php

namespace Jikan\Request\Search;

use Jikan\Request\RequestInterface;

/**
 * Class SearchRequest
 *
 * NOTE: These were the only types available at the time of coding this
 * Add to it if more searches get enabled
 *
 * @package Jikan\Request\Search
 */
class SearchRequest implements RequestInterface
{
    public const ANIME = 'anime';
    public const MANGA = 'manga';
    public const CHARACTER = 'character';
    public const PEOPLE = 'people';
    //public const NEWS = 'news'; // for later version

    /**
     * @var string
     */
    private $query;

    /**
     * @var string
     */
    private $type;
    /**
     * @var int
     */
    private $page;

    /**
     * SearchRequest constructor.
     *
     * @param string $query
     * @param string $type
     * @param int    $page
     */
    public function __construct(string $query, string $type, int $page = 0)
    {
        $this->query = $query;
        $this->type = $type;
        $this->page = $page;
    }

    /**
     * Get the path to request
     *
     * @return string
     */
    public function getPath(): string
    {
        /** @noinspection PhpUsageOfSilenceOperatorInspection */
        if (@\constant(self::class.'::'.strtoupper($this->type)) === null) {
            throw new \InvalidArgumentException('Invalid search type');
        }
        $query = http_build_query(['q' => $this->query, 'show' => $this->page ? 50 * $this->page : null]);

        return sprintf('https://myanimelist.net/%s.php?%s', $this->type, $query);
    }
}
