<?php

namespace Jikan\Model\Anime;

use Jikan\Parser\Anime\AnimeReviewParser;

/**
 * Class AnimeReview
 *
 * @package Jikan\Model
 */
class AnimeReview
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
     * @var AnimeReviewer
     */
    private $reviewer;

    /**
     * @var string
     */
    private $content;

    /**
     * Create an instance from an AnimeReviewParser parser
     *
     * @param AnimeReviewParser $parser
     *
     * @return AnimeReview
     * @throws \Exception
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public static function fromParser(AnimeReviewParser $parser): AnimeReview
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
     * @return AnimeReviewer
     */
    public function getReviewer(): AnimeReviewer
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
