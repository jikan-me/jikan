<?php

namespace Jikan\Model\Anime;

use Jikan\Parser;

/**
 * Class AnimeReviewScores
 *
 * @package Jikan\Model
 */
class AnimeReviewScores
{

    /**
     * @var int
     */
    private $overall;

    /**
     * @var int
     */
    private $story;

    /**
     * @var int
     */
    private $animation;

    /**
     * @var int
     */
    private $sound;

    /**
     * @var int
     */
    private $character;

    /**
     * @var int
     */
    private $enjoyment;

    /**
     * @param AnimeReviewScoreParser $parser
     *
     * @return AnimeReviewScores
     * @throws \InvalidArgumentException
     */
    public static function fromParser(AnimeReviewScoreParser $parser): AnimeReviewScores
    {
        $instance = new self();

        $instance->overall = $parser->getOverallScore();
        $instance->story = $parser->getStoryScore();
        $instance->animation = $parser->getAnimationScore();
        $instance->sound = $parser->getSoundScore();
        $instance->character = $parser->getCharacterScore();
        $instance->enjoyment = $parser->getEnjoymentScore();

        return $instance;
    }


}
