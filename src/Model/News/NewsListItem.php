<?php

namespace Jikan\Model\News;

use Jikan\Model\MalUrl;
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
     * @var MalUrl
     */
    private $author;

    /**
     * @var string
     */
    private $discussionLink;

    /**
     * @var string
     */
    private $image;

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
        $instance->author = $parser->getAuthor();
        $instance->discussionLink = $parser->getDiscussionLink();
        $instance->image = $parser->getImage();
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
     * @return MalUrl
     */
    public function getAuthor(): MalUrl
    {
        return $this->author;
    }

    /**
     * @return string
     */
    public function getDiscussionLink(): string
    {
        return $this->discussionLink;
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @return string
     */
    public function getIntro(): string
    {
        return $this->intro;
    }
}
