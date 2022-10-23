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
    private $person;

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
        $instance->person = $parser->getPersonMeta();
        $instance->language = $parser->getLanguage();

        return $instance;
    }

    /**
     * @return PersonMeta
     */
    public function getPerson(): PersonMeta
    {
        return $this->person;
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }
}
