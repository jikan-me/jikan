<?php


namespace Jikan\Model\User;

use Jikan\Model\Common\ItemMeta;
use Jikan\Model\Common\MalUrl;

/**
 * Class FavoriteCharacter
 *
 * @package Jikan\Model\User
 */
class FavoriteCharacter extends ItemMeta
{

    /**
     * @var MalUrl
     */
    private $malUrl;

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
        $this->malUrl = $malUrl;
    }

    /**
     * @return MalUrl
     */
    public function getMalUrl(): MalUrl
    {
        return $this->malUrl;
    }
}