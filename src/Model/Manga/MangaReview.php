<?php

namespace Jikan\Model\Manga;

use Jikan\Parser\Manga\MangaReviewParser;

/**
 * Class MangaReview
 *
 * @package Jikan\Model
 */
class MangaReview
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
     * @var int
     */
    private $helpfulCount;

    /**
     * @var \DateTimeImmutable
     */
    private $date;

    /**
     * @var MangaReviewer
     */
    private $reviewer;

    /**
     * @var string
     */
    private $content;

    /**
     * Create an instance from an MangaReviewParser parser
     *
     * @param MangaReviewParser $parser
     *
     * @return MangaReview
     * @throws \Exception
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public static function fromParser(MangaReviewParser $parser): MangaReview
    {
        $instance = new self();

        $instance->malId = $parser->getId();
        $instance->url = $parser->getUrl();
        $instance->helpfulCount= $parser->getHelpfulCount();
        $instance->date = $parser->getDate();
        $instance->reviewer = $parser->getReviewer();
        $instance->content = $parser->getContent();

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
     * @return int
     */
    public function getHelpfulCount(): int
    {
        return $this->helpfulCount;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @return MangaReviewer
     */
    public function getReviewer(): MangaReviewer
    {
        return $this->reviewer;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }
}
