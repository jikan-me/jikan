<?php

namespace Jikan\Model;

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
     * @return string
     */
    public function __toString()
    {
        return $this->name->getName();
    }

    /**
     * @param \Jikan\Parser\Person\VoiceActingRoleParser $parser
     *
     * @return VoiceActingRole
     */
    public static function fromParser(\Jikan\Parser\Person\VoiceActingRoleParser $parser): VoiceActingRole
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
