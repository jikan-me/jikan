<?php

namespace Jikan\Model;

use Jikan\Parser;

/**
 * Class SeasonalAnime
 *
 * @package Jikan\Model
 */
class SeasonalAnime extends Model
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $airDates;

    /**
     * @var int|null
     */
    private $episodes;

    /**
     * @var int
     */
    private $members;

    /**
     * @var array
     */
    private $genres;

    /**
     * @var string
     */
    private $source;

    /**
     * @var string
     */
    private $studio;

    /**
     * @param Parser\SeasonalAnime $parser
     *
     * @return SeasonalAnime
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public static function fromParser(Parser\SeasonalAnime $parser): self
    {
        $instance = new self();
        $instance->title = $parser->getTitle();
        $instance->description = $parser->getDescription();
        $instance->type = $parser->getType();
        $instance->airDates = $parser->getAirDates();
        $instance->episodes = $parser->getEpisodes();
        $instance->members = $parser->getMembers();
        $instance->genres = $parser->getGenres();
        $instance->source = $parser->getSource();
        $instance->studio = $parser->getStudio();

        return $instance;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getAirDates(): string
    {
        return $this->airDates;
    }

    /**
     * @return int|null
     */
    public function getEpisodes(): ?int
    {
        return $this->episodes;
    }

    /**
     * @return int
     */
    public function getMembers(): int
    {
        return $this->members;
    }

    /**
     * @return array
     */
    public function getGenres(): array
    {
        return $this->genres;
    }

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * @return string
     */
    public function getStudio(): string
    {
        return $this->studio;
    }
}
