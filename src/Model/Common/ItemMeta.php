<?php

namespace Jikan\Model\Common;

use Jikan\Helper\ImagePlaceholder;
use Jikan\Helper\Parser;

/**
 * Class ItemMeta
 *
 * @package Jikan\Model
 */
class ItemMeta
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
     * @var string|null
     */
    private $imageUrl;

    /**
     * @var string
     */
    private $name;

    /**
     * Genre constructor.
     *
     * @param string $name
     * @param string $url
     * @param string|null $imageUrl
     */
    public function __construct(string $name, string $url, ?string $imageUrl)
    {
        $this->url = $url;
        $this->imageUrl = ImagePlaceholder::normalize(
            $imageUrl === null ? null : Parser::parseImageQuality($imageUrl)
        );
        $this->name = $name;

        $this->malId = Parser::idFromUrl($this->url);
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
     * @return string|null
     */
    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
