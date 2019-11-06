<?php

namespace Jikan\Parser\Manga;

/**
 * Class MangaReviewerParser
 *
 * @package Jikan\Parser\Manga
 */
class MangaReviewerParser extends \Jikan\Parser\Common\ReviewerParser
{
    /**
     * @return \Jikan\Model\Manga\MangaReviewer
     */
    public function getModel(): \Jikan\Model\Manga\MangaReviewer
    {
        return \Jikan\Model\Manga\MangaReviewer::fromParser($this);
    }
}
