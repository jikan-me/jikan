<?php

namespace Jikan\Model\Character;

use Jikan\Model\Common\PersonMeta;
use Jikan\Parser\Character\VoiceActorParser;

/**
 * Class VoiceActors
 *
 * @package Jikan\Model
 */
class VoiceActor
{

    /**
     * @var PersonMeta
     */
    private $personMeta;

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
        $instance->personMeta = $parser->getPersonMeta();
        $instance->language = $parser->getLanguage();

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
