<?php

namespace Jikan\Parser\Anime;

/**
 * Class AnimeReviewerParser
 * @package Jikan\Parser\Anime
 */
class AnimeReviewerParser extends \Jikan\Parser\Common\ReviewerParser
{
    /**
     * @return \Jikan\Model\Anime\AnimeReviewer
     */
    public function getModel(): \Jikan\Model\Anime\AnimeReviewer
    {
        return \Jikan\Model\Anime\AnimeReviewer::fromParser($this);
    }
}
