<?php

namespace Jikan\Model\Common;

use Jikan\Helper\Parser;
use Jikan\Parser\Common\TagUrlParser;

/**
 * Class ItemMeta
 *
 * @package Jikan\Model
 */
class TagMeta
{
    /**
     * @var string
     */
    private string $name;
    /**
     * @var string
     */
    private string $malId;
    /**
     * @var string
     */
    private string $url;
    /**
     * @var string
     */
    private string $type;
    /**
     * @var string|null
     */
    private ?string $description;


    /**
     * @param string $name
     * @param string $url
     * @param string $type
     * @param string|null $description
     */
    public function __construct(string $name, string $url, string $type, ?string $description)
    {
        $this->url = $url;
        $this->name = $name;
        $this->type = $type;
        $this->description = $description;
        $this->malId = Parser::stringIdFromUrl($this->url);
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
    public function getMalId(): string
    {
        return $this->malId;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

}
