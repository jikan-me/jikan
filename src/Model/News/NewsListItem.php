<?php

namespace Jikan\Model\News;

use Jikan\Model\Resource\NewsImageResource\NewsImageResource;
use Jikan\Model\Resource\WrapImageResource\WrapImageResource;
use Jikan\Parser\News\NewsListItemParser;
use Jikan\Parser\News\ResourceNewsListItemParser;

/**
 * Class AnimeParser
 *
 * @package Jikan\Model
 */
class NewsListItem
{
    /**
     * @var int|null
     */
    private int|null $malId;

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
     * @var NewsImageResource
     */
    private NewsImageResource $images;

    /**
     * @var int
     */
    private int $comments;

    /**
     * @var string
     */
    private string $excerpt;

    /**
     * @var array
     */
    private array $tags;

    /**
     * @param NewsListItemParser $parser
     *
     * @return NewsListItem
     * @throws \InvalidArgumentException
     */
    public static function fromParser(NewsListItemParser $parser): NewsListItem
    {
        $instance = new self();
        $instance->malId = $parser->getMalId();
        $instance->url = $parser->getUrl();
        $instance->title = $parser->getTitle();
        $instance->comments = $parser->getComments();
        $instance->tags = $parser->getTags();
        $instance->authorUsername = $parser->getAuthor()->getName();
        $instance->authorUrl = $parser->getAuthor()->getUrl();
        $instance->forumUrl = $parser->getDiscussionLink();
        $instance->images = NewsImageResource::factory($parser->getImageUrl());
        $instance->excerpt = $parser->getExcerpt();
        $instance->date = $parser->getDate();

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
     * @return NewsImageResource
     */
    public function getImages(): NewsImageResource
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
     * @return string
     */
    public function getExcerpt(): string
    {
        return $this->excerpt;
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }
}
