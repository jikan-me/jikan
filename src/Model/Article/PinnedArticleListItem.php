<?php

namespace Jikan\Model\Article;

use Jikan\Model\Resource\NewsImageResource\NewsImageResource;
use Jikan\Model\Resource\WrapImageResource\WrapImageResource;
use Jikan\Parser\Article\PinnedArticleListItemParser;

/**
 * Class PinnedArticleListItem
 *
 * @package Jikan\Model
 */
class PinnedArticleListItem
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
     * @var string
     */
    private string $authorUsername;

    /**
     * @var string
     */
    private string $authorUrl;

    /**
     * @var WrapImageResource
     */
    private WrapImageResource $images;

    /**
     * @var int
     */
    private int $views;

    /**
     * @var string
     */
    private string $excerpt;

    /**
     * @var array
     */
    private array $tags;


    /**
     * @param PinnedArticleListItemParser $parser
     * @return PinnedArticleListItem
     */
    public static function fromParser(PinnedArticleListItemParser $parser): PinnedArticleListItem
    {
        $instance = new self();
        $instance->malId = $parser->getMalId();
        $instance->url = $parser->getUrl();
        $instance->title = $parser->getTitle();
        $instance->views = $parser->getViews();
        $instance->tags = $parser->getTags();
        $instance->authorUsername = $parser->getAuthor()->getName();
        $instance->authorUrl = $parser->getAuthor()->getUrl();
        $instance->images = WrapImageResource::factory($parser->getImageUrl());
        $instance->excerpt = $parser->getExcerpt();

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
     * @return NewsImageResource
     */
    public function getImages(): NewsImageResource
    {
        return $this->images;
    }

    /**
     * @return int
     */
    public function getViews(): int
    {
        return $this->views;
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
