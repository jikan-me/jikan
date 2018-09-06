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
     * SeasonalRequest constructor.
     *
     * @param ?int    $year
     * @param ?string $season
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(?int $year = null, ?string $season = null)
    {
        if (!\in_array($season, ['winter', 'spring', 'summer', 'fall', null], true)) {
            throw new \InvalidArgumentException(sprintf('Season %s is not valid', $season));
        }
        $this->year = $year;
        $this->season = $season;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        if (is_null($this->year) || is_null($this->season)) {
            return sprintf('https://myanimelist.net/anime/season');
        }
        return sprintf('https://myanimelist.net/anime/season/%s/%s', $this->year, $this->season);
    }
}
