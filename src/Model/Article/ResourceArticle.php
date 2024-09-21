<?php

namespace Jikan\Model\Article;

use Jikan\Model\Common\MalUrl;
use Jikan\Model\Resource\WrapImageResource\WrapImageResource;
use Jikan\Parser\Article\ArticleParser;
use Jikan\Parser\News\NewsParser;

/**
 * Class ResourceArticle
 *
 * @package Jikan\Model
 */
class ResourceArticle
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
     * @var string
     */
    private string $excerpt;

    /**
     * @var \DateTimeImmutable
     */
    private \DateTimeImmutable $date;

    /**
     * @var string
     */
    private string $authorUsername;

    /**
     * @var string|null
     */
    private ?string $authorUrl;

    /**
     * @var WrapImageResource
     */
    private WrapImageResource $images;

    /**
     * @var int
     */
    private int $views;

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
    public function getExcerpt(): string
    {
        return $this->excerpt;
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
     * @return WrapImageResource
     */
    public function getImages(): WrapImageResource
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
    public function getRelatedArticles(): array
    {
        return $this->relatedArticles;
    }

    /**
     * @var array
     */
    private array $relatedArticles = [];


    /**
     * @param ArticleParser $parser
     * @return self
     */
    public static function fromParser(ArticleParser $parser): self
    {
        $instance = new self();

        $instance->malId = $parser->getMalId();
        $instance->url = $parser->getUrl();
        $instance->title = $parser->getTitle();
        $instance->excerpt = $parser->getExcerpt();
        $instance->authorUsername = $parser->getAuthor()->getName();
        $instance->authorUrl = $parser->getAuthor()->getUrl();
        $instance->images = WrapImageResource::factory($parser->getImageUrl());
        $instance->views = $parser->getViews();
        $instance->content = $parser->getContent();
        $instance->date = $parser->getDate();
        $instance->tags = $parser->getTags();
        $instance->relatedEntries = $parser->getRelatedEntries();
        $instance->relatedArticles = $parser->getRelatedArticles();

        return $instance;
    }


}
