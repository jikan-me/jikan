<?php

namespace Jikan\Model\Common;

use Jikan\Parser\Common\MalUrlParser;

/**
 * Class MalUrl
 *
 * @package Jikan\Model
 */
class MalUrl
{
    /**
     * @var string
     */
    private $name;

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
    public function __construct(string $name, string $url)
    {
        $this->name = $name;
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getMalId(): int
    {
        return MalUrlParser::parseId($this->url);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return preg_replace('#https://myanimelist.net/(\w+)/.*#', '$1', $this->url);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }
}
