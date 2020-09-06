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
     * @var string
     */
    private $titleName;

    /**
     * @var string
     */
    private $titleUrl;

    /**
     * @var string
     */
    private $titleType;

    /**
     * @var int
     */
    private $titleMalId;


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
        $this->titleName = $malUrl->getTitle();
        $this->titleUrl = $malUrl->getUrl();
        $this->titleType = $malUrl->getType();
        $this->titleMalId = $malUrl->getMalId();
    }

    /**
     * @return string
     */
    public function getTitleUrl(): string
    {
        return $this->titleUrl;
    }

    /**
     * @return string
     */
    public function getTitleName(): string
    {
        return $this->titleName;
    }

    /**
     * @return string
     */
    public function getTitleType(): string
    {
        return $this->titleType;
    }

    /**
     * @return int
     */
    public function getTitleMalId(): int
    {
        return $this->titleMalId;
    }
}