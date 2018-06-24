<?php

namespace Jikan\Parser;

/**
 * Class AnimeEpisodes
 *
 * @package Jikan\Parser
 */
class AnimeEpisodes extends \Skraypar\Skraypar
{
    public $model;

    /**
     * AnimeEpisodes constructor.
     *
     * @param $model
     */
    public function __construct(&$model)
    {
        $this->model = &$model;
    }

    public function loadRules()
    {
    }
}