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
     * @var int
     */
    private $comments = 0;

    /**
     * @var ForumPost
     */
    private $lastComment;

    /**
     * @param ForumTopicParser $parser
     *
     * @return ForumTopic
     * @throws \InvalidArgumentException
     */
    public static function fromParser(ForumTopicParser $parser): self
    {
        $instance = new self();
        $instance->malId = $parser->getTopicId();
        $instance->url = $parser->getUrl();
        $instance->title = $parser->getTitle();
        $instance->date = $parser->getPostDate();
        $instance->comments = $parser->getReplies();
        $instance->authorUsername = $parser->getAuthorName();
        $instance->authorUrl = $parser->getAuthorUrl();
        $instance->lastComment = $parser->getLastPost();

        return $instance;
    }

    /**
     * @return int
     */
    public function getMalId(): int
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
     * @return int
     */
    public function getComments(): int
    {
        return $this->comments;
    }

    /**
     * @return ForumPost
     */
    public function getLastComment(): ForumPost
    {
        return $this->lastComment;
    }
}
