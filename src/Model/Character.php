<?php

namespace Jikan\Model;

/**
 * Class Character
 *
 * @package Jikan\Model
 */
class Character
{
    /**
     * @var int
     */
    public $mal_id;

    /**
     * @var string
     */
    public $link_canonical;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $name_kanji;

    /**
     * @var array
     */
    public $nicknames = [];

    /**
     * @var string
     */
    public $about;

    /**
     * @var int
     */
    public $member_favorites;

    /**
     * @var string
     */
    public $image_url;

    /**
     * @var array
     */
    public $animeography = [];

    /**
     * @var array
     */
    public $mangaography = [];

    /**
     * @var array
     */
    public $voice_actor = [];

}