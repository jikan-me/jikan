<?php

namespace Jikan\Model\News;

use Jikan\Model\Resource\WrapImageResource\WrapImageResource;
use Jikan\Parser\News\NewsListItemParser;

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
     * @var \DateTimeImmutable
     */
    private $date;

    /**
     * @var string
     */
    private $authorUsername;

    /**
     * @var string
     */
    private $authorUrl;

    /**
     * @var string
     */
    private $forumUrl;

    /**
     * @var WrapImageResource
     */
    private $images;

    /**
     * @var int
     */
    private $comments;

    /**
     * @var string
     */
    private $excerpt;

    /**
     * @param NewsListItemParser $parser
     *
     * @return NewsListItem
     * @throws \InvalidArgumentException
     */
    public static function fromParser(NewsListItemParser $parser): self
    {
        $instance = new self();
        $instance->malId = $parser->getMalId();
        $instance->url = $parser->getUrl();
        $instance->title = $parser->getTitle();
        $instance->date = $parser->getDate();
        $instance->authorUsername = $parser->getAuthor()->getName();
        $instance->authorUrl = $parser->getAuthor()->getUrl();
        $instance->forumUrl = $parser->getDiscussionLink();
        $instance->images = WrapImageResource::factory($parser->getImage());
        $instance->comments = $parser->getComments();
        $instance->excerpt = $parser->getIntro();

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
     * @return string
     */
    public function getExcerpt(): string
    {
        return $this->excerpt;
    }
}
