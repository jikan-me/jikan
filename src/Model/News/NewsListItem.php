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
    private $authorName;

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
    private $intro;

    /**
     * @param NewsListItemParser $parser
     *
     * @return NewsListItem
     * @throws \InvalidArgumentException
     */
    public static function fromParser(NewsListItemParser $parser): self
    {
        $instance = new self();
        $instance->url = $parser->getUrl();
        $instance->title = $parser->getTitle();
        $instance->date = $parser->getDate();
        $instance->authorName = $parser->getAuthor()->getName();
        $instance->authorUrl = $parser->getAuthor()->getUrl();
        $instance->forumUrl = $parser->getDiscussionLink();
        $instance->images = WrapImageResource::factory($parser->getImage());
        $instance->comments = $parser->getComments();
        $instance->intro = $parser->getIntro();

        return $instance;
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
    public function getAuthorName(): string
    {
        return $this->authorName;
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
    public function getIntro(): string
    {
        return $this->intro;
    }
}
