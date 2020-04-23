<?php

namespace Jikan\Model\Watch;

/**
 * Class RecentEpisodeListItem
 *
 * @package Jikan\Model
 */
class RecentEpisodeListItem
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
     * @var string
     */
    private $title;

    /**
     * @var bool
     */
    private $premium;

    /**
     * @param int $malId
     * @param string $url
     * @param string $title
     * @param bool $premium
     *
     * @return self
     * @throws \Exception
     */
    public static function factory(int $malId, string $url, string $title, bool $premium): self
    {
        $instance = new self();

        $instance->malId = $malId;
        $instance->url = $url;
        $instance->title = $title;
        $instance->premium = $premium;

        return $instance;
    }
}
