<?php

namespace Jikan\Model\News;

use Jikan\Model\Resource\WrapImageResource\WrapImageResource;
use Jikan\Parser\News\ResourceNewsListItemParser;

/**
 * Class AnimeParser
 *
 * @package Jikan\Model
 */
class ResourceNewsListItem
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
     * @var string
     */
    private string $excerpt;

    /**
     * @param ResourceNewsListItemParser $parser
     *
     * @return ResourceNewsListItem
     * @throws \InvalidArgumentException
     */
    public static function fromParser(ResourceNewsListItemParser $parser): self
    {
        $instance = new self();
        $instance->malId = $parser->getMalId();
        $instance->url = $parser->getUrl();
        $instance->title = $parser->getTitle();
        $instance->authorUsername = $parser->getAuthor()->getName();
        $instance->authorUrl = $parser->getAuthor()->getUrl();
        $instance->forumUrl = $parser->getDiscussionLink();
        $instance->images = WrapImageResource::factory($parser->getImage());
        $instance->comments = $parser->getComments();
        $instance->excerpt = $parser->getIntro();
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
