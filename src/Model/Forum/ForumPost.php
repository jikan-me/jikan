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
    private $authorUsername;

    /**
     * @var string
     */
    private $authorUrl;

    /**
     * @var \DateTimeImmutable|null
     */
    private $date;

    /**
     * ForumPost constructor.
     *
     * @param string                  $url
     * @param string                  $authorUsername
     * @param string                  $authorUrl
     * @param \DateTimeImmutable|null $relativeDate
     */
    public function __construct(string $url, string $authorUsername, string $authorUrl, ?\DateTimeImmutable $relativeDate)
    {
        $this->url = $url;
        $this->authorUsername = $authorUsername;
        $this->authorUrl = $authorUrl;
        $this->date = $relativeDate;
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
     * @return \DateTimeImmutable|null
     */
    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }
}
