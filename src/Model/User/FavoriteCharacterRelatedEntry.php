<?php

namespace Jikan\Model\User;

use Jikan\Model\Common\MalUrl;
use Jikan\Parser\Common\MalUrlParser;

/**
 * Class MalUrl
 *
 * @package Jikan\Model
 */
class FavoriteCharacterRelatedEntry
{
    /**
     * @var int
     */
    private $malId;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $url;

    /**
     * Genre constructor.
     *
     * @param string $name
     * @param string $url
     */
    public function __construct(MalUrl $malUrl)
    {
        $this->malId = $malUrl->getMalId();
        $this->title = $malUrl->getTitle();
        $this->type = $malUrl->getType();
        $this->url = $malUrl->getUrl();
    }

    /**
     * @return int
     */
    public function getMalId(): int
    {
        return $this->malId;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }
}
