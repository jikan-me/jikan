<?php

namespace Jikan\Model;

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
    private $animeMeta;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name->getName();
    }

    /**
     * @param \Jikan\Parser\Person\AnimeStaffPositionParser $parser
     *
     * @return AnimeStaffPosition
     */
    public static function fromParser(\Jikan\Parser\Person\AnimeStaffPositionParser $parser): AnimeStaffPosition
    {
        $instance = new self();
        $instance->position = $parser->getPosition();
        $instance->animeMeta = $parser->getAnimeMeta();

        return $instance;
    }

    /**
     * @return string
     */
    public function getPosition(): string
    {
        return $this->role;
    }

    /**
     * @return AnimeMeta
     */
    public function getAnimeMeta(): AnimeMeta
    {
        return $this->animeMeta;
    }
}
