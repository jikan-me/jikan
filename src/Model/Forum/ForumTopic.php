<?php

namespace Jikan\Model\Forum;

use Jikan\Parser\Forum\ForumTopicParser;

/**
 * Class ForumPost
 *
 * @package Jikan\Model\Anime\Anime
 */
class ForumTopic
{
    /**
     * @var int
     */
    private $topicId;

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
    private $datePosted;

    /**
     * @var string
     */
    private $authorName;

    /**
     * @var string
     */
    private $authorUrl;

    /**
     * @var int
     */
    private $replies = 0;

    /**
     * @var ForumPost
     */
    private $lastPost;

    /**
     * @param ForumTopicParser $parser
     *
     * @return ForumTopic
     * @throws \InvalidArgumentException
     */
    public static function fromParser(ForumTopicParser $parser): self
    {
        $instance = new self();
        $instance->topicId = $parser->getTopicId();
        $instance->url = $parser->getUrl();
        $instance->title = $parser->getTitle();
        $instance->datePosted = $parser->getPostDate();
        $instance->replies = $parser->getReplies();
        $instance->authorName = $parser->getAuthorName();
        $instance->authorUrl = $parser->getAuthorUrl();
        $instance->lastPost = $parser->getLastPost();

        return $instance;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->title;
    }

    /**
     * @return int
     */
    public function getTopicId(): int
    {
        return $this->topicId;
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
    public function getDatePosted(): \DateTimeImmutable
    {
        return $this->datePosted;
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
     * @return int
     */
    public function getReplies(): int
    {
        return $this->replies;
    }

    /**
     * @return ForumPost
     */
    public function getLastPost(): ForumPost
    {
        return $this->lastPost;
    }
}
