<?php

namespace Jikan\Model\Character;

use Jikan\Parser\Character\VoiceActorParser;

/**
 * Class VoiceActors
 *
 * @package Jikan\Model
 */
class VoiceActor
{
    /**
     * @var int
     */
    private $malId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $imageUrl;

    /**
     * @var string
     */
    private $language;

    /**
     * @param VoiceActorParser $parser
     *
     * @return VoiceActor
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public static function fromParser(VoiceActorParser $parser): VoiceActor
    {
        $instance = new self();
        $instance->malId = $parser->getMalId();
        $instance->url = $parser->getUrl();
        $instance->name = $parser->getName();
        $instance->imageUrl = $parser->getImage();
        $instance->language = $parser->getLanguage();
        $instance->url = $parser->getUrl();

        return $instance;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return string
     */
    public function getName(): string
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
     * @return string
     */
    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }
}
