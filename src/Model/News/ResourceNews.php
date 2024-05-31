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
}
