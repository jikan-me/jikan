<?php

namespace Jikan\Model\Person;

use Jikan\Model\Common\AnimeMeta;
use Jikan\Model\Common\CharacterMeta;
use Jikan\Parser\Person\VoiceActingRoleParser;

/**
 * Class VoiceActingRole
 *
 * @package Jikan\Model
 */
class VoiceActingRole
{
    /**
     * @var string
     */
    private $role;

    /**
     * @var AnimeMeta
     */
    private $animeMeta;

    /**
     * @var CharacterMeta
     */
    private $characterMeta;

    /**
     * @param VoiceActingRoleParser $parser
     *
     * @return VoiceActingRole
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public static function fromParser(VoiceActingRoleParser $parser): VoiceActingRole
    {
        $instance = new self();
        $instance->role = $parser->getRole();
        $instance->animeMeta = $parser->getAnimeMeta();
        $instance->characterMeta = $parser->getCharacterMeta();

        return $instance;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @return AnimeMeta
     */
    public function getAnimeMeta(): AnimeMeta
    {
        return $this->animeMeta;
    }

    /**
     * @return CharacterMeta
     */
    public function getCharacterMeta(): CharacterMeta
    {
        return $this->characterMeta;
    }
}
