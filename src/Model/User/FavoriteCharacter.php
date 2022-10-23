<?php


namespace Jikan\Model\User;

use Jikan\Model\Common\CharacterMeta;
use Jikan\Model\Common\MalUrl;

/**
 * Class FavoriteCharacter
 *
 * @package Jikan\Model\User
 */
class FavoriteCharacter extends CharacterMeta
{
    /**
     * @var FavoriteCharacterRelatedEntry
     */
    private $entry;


    /**
     * FavoriteCharacter constructor.
     *
     * @param string $name
     * @param string $url
     * @param string $imageUrl
     * @param MalUrl $malUrl
     */
    public function __construct(string $name, string $url, string $imageUrl, MalUrl $malUrl)
    {
        parent::__construct($name, $url, $imageUrl);
        $this->entry = new FavoriteCharacterRelatedEntry($malUrl);
    }
}
