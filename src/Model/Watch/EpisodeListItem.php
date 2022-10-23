<?php

namespace Jikan\Model\Watch;

use Jikan\Model\Common\AnimeMeta;
use Jikan\Parser\ParserInterface;
use Jikan\Parser\Watch\EpisodeListItemParser;

/**
 * Class AnimeListItem
 *
 * @package Jikan\Model
 */
class EpisodeListItem
{
    /**
     * @var AnimeMeta
     */
    private $entry;

    /**
     * @var RecentEpisodeListItem
     */
    private $episodes;

    /**
     * @var bool
     */
    private $regionLocked;

    /**
     * @param EpisodeListItemParser $parser
     *
     * @return EpisodeListItem
     * @throws \Exception
     */
    public static function fromParser(ParserInterface $parser): EpisodeListItem
    {
        $instance = new self();
        $instance->entry = $parser->getAnimeMeta();
        $instance->episodes = $parser->getEpisodes();
        $instance->regionLocked = $parser->getRegionLocked();

        return $instance;
    }

    /**
     * @return AnimeMeta
     */
    public function getEntry(): AnimeMeta
    {
        return $this->entry;
    }

    /**
     * @return RecentEpisodeListItem
     */
    public function getEpisodes(): RecentEpisodeListItem
    {
        return $this->episodes;
    }

    /**
     * @return bool
     */
    public function isRegionLocked(): bool
    {
        return $this->regionLocked;
    }
}
