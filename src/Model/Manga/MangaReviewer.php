<?php

namespace Jikan\Model\Manga;

use Jikan\Model\Common\Reviewer;
use Jikan\Model\Resource\UserImageResource\UserImageResource;
use Jikan\Parser\Manga\MangaReviewerParser;

/**
 * Class MangaReviewer
 *
 * @package Jikan\Model
 */
class MangaReviewer extends Reviewer
{
    /**
     * @var int
     */
    private $chaptersRead;

    /**
     * @var MangaReviewScores
     */
//    private $scores;

    /**
     * @param MangaReviewerParser $parser
     *
     * @return MangaReviewer
     * @throws \InvalidArgumentException
     */
    public static function fromParser(MangaReviewerParser $parser): MangaReviewer
    {
        $instance = new self();

        $instance->url= $parser->getUrl();
        $instance->images = UserImageResource::factory($parser->getImageUrl());
        $instance->username = $parser->getUsername();
        $instance->chaptersRead = $parser->getChaptersRead();
//        $instance->scores = $parser->getMangaScores();

        return $instance;
    }

    /**
     * @return int
     */
    public function getChaptersRead(): int
    {
        return $this->chaptersRead;
    }

    /**
     * @return MangaReviewScores
     */
    public function getScores(): MangaReviewScores
    {
        return $this->scores;
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
    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }
}
