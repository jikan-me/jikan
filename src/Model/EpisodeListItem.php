<?php

namespace Jikan\Model;

use Jikan\Parser\Anime\EpisodeListItemParser;

/**
 * Class EpisodeParser
 *
 * @package Jikan\Model
 */
class EpisodeListItem
{
    /**
     * @var int
     */
    public $episodeId;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $titleJapanese;

    /**
     * @var string
     */
    public $titleRomanji;

    /**
     * @var \DateTimeImmutable
     */
    public $aired;

    /**
     * @var bool
     */
    public $filler;

    /**
     * @var bool
     */
    public $recap;
    
    /**
     * @var string
     */
    public $videoUrl;

    /**
     * @var string
     */
    public $forumUrl;

    /**
     * @param EpisodeListItemParser $parser
     *
     * @return EpisodeListItem
     */
    public static function fromParser(EpisodeListItemParser $parser): self
    {
        $instance = new self();
        $instance->episodeId = $parser->getEpisodeId();
        $instance->title = $parser->getTitle();
        $instance->titleJapanese = $parser->getTitleJapanese();
        $instance->titleRomanji = $parser->getTitleRomanji();
        $instance->aired = $parser->getAired();
        $instance->filler = $parser->getFiller();
        $instance->recap = $parser->getRecap();
        $instance->videoUrl = $parser->getVideoUrl();
        $instance->forumUrl = $parser->getForumUrl();

        return $instance;
    }

    /**
     * @return int
     */
    public function getEpisodeId(): int
    {
        return $this->episodeId;
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
    public function getTitleJapanese(): string
    {
        return $this->titleJapanese;
    }

    /**
     * @return string
     */
    public function getTitleRomanji(): string
    {
        return $this->titleRomanji;
    }

    /**
     * @return DateRange
     */
    public function getAired(): DateRange
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
     * @return string
     */
    public function getVideoUrl(): string
    {
        return $this->videoUrl;
    }

    /**
     * @return string
     */
    public function getForumUrl(): string
    {
        return $this->forumUrl;
    }
}
