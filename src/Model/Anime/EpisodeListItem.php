<?php

namespace Jikan\Model\Anime;

use Jikan\Model\Common\DateRange;
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
    public int $malId;

    /**
     * @var string|null
     */
    private ?string $url;

    /**
     * @var string
     */
    public string $title;

    /**
     * @var string|null
     */
    public ?string $titleJapanese;

    /**
     * @var string|null
     */
    public ?string $titleRomanji;

    /**
     * @var \DateTimeImmutable|null
     */
    public ?\DateTimeImmutable $aired;

    /**
     * @var float|null
     */
    public ?float $score;

    /**
     * @var bool
     */
    public bool $filler;

    /**
     * @var bool
     */
    public bool $recap;

    /**
     * @var string|null
     */
    public ?string $forumUrl;


    /**
     * @param  EpisodeListItemParser $parser
     * @return EpisodeListItem
     * @throws \Exception
     */
    public static function fromParser(EpisodeListItemParser $parser): self
    {
        $instance = new self();
        $instance->malId = $parser->getEpisodeId();
        $instance->title = $parser->getTitle();
        $instance->titleJapanese = $parser->getTitleJapanese();
        $instance->titleRomanji = $parser->getTitleRomanji();
        $instance->aired = $parser->getAired();
        $instance->score = $parser->getScore();
        $instance->filler = $parser->getFiller();
        $instance->recap = $parser->getRecap();
        $instance->url = $parser->getVideoUrl();
        $instance->forumUrl = $parser->getForumUrl();

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
     * @return \DateTimeImmutable|null
     */
    public function getAired()
    {
        return $this->aired;
    }

    /**
     * @return float
     */
    public function getScore(): float
    {
        return $this->score;
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
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getForumUrl(): string
    {
        return $this->forumUrl;
    }
}
