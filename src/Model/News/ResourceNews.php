<?php

namespace Jikan\Model\News;

use Jikan\Model\Common\MalUrl;
use Jikan\Model\Resource\WrapImageResource\WrapImageResource;
use Jikan\Parser\News\NewsParser;

/**
 * Class AnimeParser
 *
 * @package Jikan\Model
 */
class ResourceNews
{
    /**
     * @var int|null
     */
    private ?int $malId;

    /**
     * @var string
     */
    private string $url;

    /**
     * @var string
     */
    private string $title;

    /**
     * @var \DateTimeImmutable
     */
    private \DateTimeImmutable $date;

    /**
     * @var string
     */
    private string $authorUsername;

    /**
     * @var string
     */
    private string $authorUrl;

    /**
     * @var string
     */
    private string $forumUrl;

    /**
     * @var WrapImageResource
     */
    private WrapImageResource $images;

    /**
     * @var int
     */
    private int $comments;

    /**
     * @var array
     */
    private array $tags;

    /**
     * @var string
     */
    private string $content;

    /**
     * @var MalUrl[]
     */
    private array $relatedEntries = [];

    private array $relatedNews = [];


    /**
     * @param NewsParser $parser
     * @return self
     */
    public static function fromParser(NewsParser $parser): self
    {
        $instance = new self();

        $instance->malId = $parser->getMalId();
        $instance->url = $parser->getUrl();
        $instance->title = $parser->getTitle();
        $instance->authorUsername = $parser->getAuthor()->getName();
        $instance->authorUrl = $parser->getAuthor()->getUrl();
        $instance->forumUrl = $parser->getDiscussionLink();
        $instance->images = WrapImageResource::factory($parser->getImageUrl());
        $instance->comments = $parser->getComments();
        $instance->content = $parser->getContent();
        $instance->date = $parser->getDate();
        $instance->tags = $parser->getTags();
        $instance->relatedEntries = $parser->getRelatedEntries();
        $instance->relatedNews = $parser->getRelatedNews();

        return $instance;
    }

    /**
     * @return int|null
     */
    public function getMalId(): ?int
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
     * @return \DateTimeImmutable
     */
    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getAuthorUsername(): string
    {
        return $this->authorUsername;
    }

    /**
     * @return string
     */
    public function getAuthorUrl(): string
    {
        return $this->authorUrl;
    }

    /**
     * @return string
     */
    public function getForumUrl(): string
    {
        return $this->forumUrl;
    }

    /**
     * @return WrapImageResource
     */
    public function getImages(): WrapImageResource
    {
        return $this->images;
    }

    /**
     * @return int
     */
    public function getComments(): int
    {
        return $this->comments;
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return array
     */
    public function getRelatedEntries(): array
    {
        return $this->relatedEntries;
    }

    /**
     * @return array
     */
    public function getRelatedNews(): array
    {
        return $this->relatedNews;
    }
}
