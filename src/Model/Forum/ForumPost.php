<?php


namespace Jikan\Model\Forum;

/**
 * Class ForumPost
 *
 * @package Jikan\Model\Forum
 */
class ForumPost
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $authorName;

    /**
     * @var string
     */
    private $authorUrl;

    /**
     * @var \DateTimeImmutable|null
     */
    private $datePosted;

    /**
     * ForumPost constructor.
     *
     * @param string                  $url
     * @param string                  $authorName
     * @param string                  $authorUrl
     * @param \DateTimeImmutable|null $relativeDate
     */
    public function __construct(string $url, string $authorName, string $authorUrl, ?\DateTimeImmutable $relativeDate)
    {
        $this->url = $url;
        $this->authorName = $authorName;
        $this->authorUrl = $authorUrl;
        $this->datePosted = $relativeDate;
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
     * @return \DateTimeImmutable|null
     */
    public function getDatePosted(): ?\DateTimeImmutable
    {
        return $this->datePosted;
    }
}
