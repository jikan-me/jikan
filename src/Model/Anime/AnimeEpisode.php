<?php

namespace Jikan\Model\Anime;

use Jikan\Parser\Anime\AnimeEpisodeParser;

/**
 * Class AnimeEpisode
 *
 * @package Jikan\Model
 */
class AnimeEpisode
{
    /**
     * @var int
     */
    private $malId;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string|null
     */
    private $titleJapanese;

    /**
     * @var string|null
     */
    private $titleRomanji;

    /**
     * @var int|null
     */
    private $duration;

    /**
     * @var \DateTimeImmutable|null
     */
    private $aired;

    /**
     * @var bool
     */
    private $filler;

    /**
     * @var bool
     */
    private $recap;

    /**
     * @var string|null
     */
    private $synopsis;


    /**
     * @param  AnimeEpisodeParser $parser
     * @return AnimeEpisode
     * @throws \Exception
     */
    public static function fromParser(AnimeEpisodeParser $parser): self
    {
        $instance = new self();

        $instance->malId = $parser->getEpisodeId();
        $instance->url = $parser->getEpisodeUrl();
        $instance->filler = $parser->getFiller();
        $instance->recap = $parser->getRecap();
        $instance->title = $parser->getTitle();
        $instance->titleJapanese = $parser->getTitleJapanese();
        $instance->titleRomanji = $parser->getTitleRomanji();
        $instance->duration = $parser->getDuration();
        $instance->aired = $parser->getAired();
        $instance->synopsis = $parser->getSynopsis();

        //        $instance->characters = $parser->getCharacters();
        //        $instance->staff = $parser->getStaff();
        //        $instance->forumUrl = $parser->getForumUrl();

        return $instance;
    }

    /**
     * @return int
     */
    public function getMalId(): int
    {
        return $this->malId;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string|null
     */
    public function getTitleJapanese(): ?string
    {
        return $this->titleJapanese;
    }

    /**
     * @return string|null
     */
    public function getTitleRomanji(): ?string
    {
        return $this->titleRomanji;
    }

    /**
     * @return int|null
     */
    public function getDuration(): ?int
    {
        return $this->duration;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getAired(): ?\DateTimeImmutable
    {
        return $this->aired;
    }

    /**
     * @return bool
     */
    public function isFiller(): bool
    {
        return $this->filler;
    }

    /**
     * @return bool
     */
    public function isRecap(): bool
    {
        return $this->recap;
    }

    /**
     * @return string|null
     */
    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }
}
