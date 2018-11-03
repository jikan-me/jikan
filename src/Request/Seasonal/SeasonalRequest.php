<?php

namespace Jikan\Request\Seasonal;

use Jikan\Request\RequestInterface;

/**
 * Class SeasonalRequest
 *
 * @package Jikan\Request
 */
class SeasonalRequest implements RequestInterface
{
    /**
     * @var ?int
     */
    private $year;

    /**
     * @var ?string
     */
    private $season;

    /**
     * @var bool
     */
    private $later;

    /**
     * SeasonalRequest constructor.
     *
     * @param ?int    $year
     * @param ?string $season
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(?int $year = null, ?string $season = null, bool $later = false)
    {
        if (!\in_array($season, ['winter', 'spring', 'summer', 'fall', null], true)) {
            throw new \InvalidArgumentException(sprintf('Season %s is not valid', $season));
        }

        $this->year = $year;
        $this->season = $season;
        $this->later = $later;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        if ($this->later) {
            return sprintf('https://myanimelist.net/anime/season/later');
        }

        if (is_null($this->year) || is_null($this->season)) {
            return sprintf('https://myanimelist.net/anime/season');
        }

        return sprintf('https://myanimelist.net/anime/season/%s/%s', $this->year, $this->season);
    }
}
