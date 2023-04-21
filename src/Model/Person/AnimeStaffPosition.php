<?php

namespace Jikan\Model\Person;

use Jikan\Model\Common\AnimeMeta;
use Jikan\Parser\Person\AnimeStaffPositionParser;

/**
 * Class AnimeStaffPosition
 *
 * @package Jikan\Model
 */
class AnimeStaffPosition
{
    /**
     * @var string
     */
    private $position;

    /**
     * @var AnimeMeta
     */
    private $anime;

    /**
     * @param AnimeStaffPositionParser $parser
     *
     * @return AnimeStaffPosition
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public static function fromParser(AnimeStaffPositionParser $parser): AnimeStaffPosition
    {
        $instance = new self();
        $instance->position = $parser->getPosition();
        $instance->anime = $parser->getAnimeMeta();

        return $instance;
    }

    /**
     * @return string
     */
    public function getPosition(): string
    {
        return $this->position;
    }

    /**
     * @return AnimeMeta
     */
    public function getAnimeMeta(): AnimeMeta
    {
        return $this->anime;
    }
}
