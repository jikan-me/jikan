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
     * @param Parser\Reviews\AnimeReviewScoresParser $parser
     *
     * @return AnimeReviewScores
     * @throws \InvalidArgumentException
     */
    public static function fromParser(Parser\Reviews\AnimeReviewScoresParser $parser): AnimeReviewScores
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

    /**
     * @return int
     */
    public function getOverall(): int
    {
        return $this->overall;
    }

    /**
     * @return int
     */
    public function getStory(): int
    {
        return $this->story;
    }

    /**
     * @return int
     */
    public function getAnimation(): int
    {
        return $this->animation;
    }

    /**
     * @return int
     */
    public function getSound(): int
    {
        return $this->sound;
    }

    /**
     * @return int
     */
    public function getCharacter(): int
    {
        return $this->character;
    }

    /**
     * @return int
     */
    public function getEnjoyment(): int
    {
        return $this->enjoyment;
    }
}
