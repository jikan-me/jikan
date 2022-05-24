<?php

namespace Jikan\Model\User;

use Jikan\Model\Common\CommonMeta;
use Jikan\Model\Common\ItemMeta;

/**
 * Class FavoriteListEntry
 *
 * @package Jikan\Model\User
 */
class FavoriteListEntry extends CommonMeta
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var int
     */
    private $startYear;

    /**
     * FavoriteListEntry constructor.
     * @param string $name
     * @param string $url
     * @param string $imageUrl
     * @param string $typeAndYear
     */
    public function __construct(string $name, string $url, string $imageUrl, string $typeAndYear)
    {
        parent::__construct($name, $url, $imageUrl);
        $typeAndYearArr = explode("·", $typeAndYear);
        $this->type = trim($typeAndYearArr[0]);
        $this->startYear = (int) trim($typeAndYearArr[1]);
    }

    /**
     * @return string
     */
    public function getEntityType(): string
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getStartYear(): int
    {
        return $this->startYear;
    }
}
