<?php

namespace Jikan\Model\Club;

use Jikan\Parser\Club\UserProfileParser;

/**
 * Class UserProfile
 *
 * @package Jikan\Model\Club
 */
class UserProfile
{
    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $image;

    /**
     * @param UserProfileParser $parser
     *
     * @return UserProfile
     */
    public static function fromParser(UserProfileParser $parser): self
    {
        $instance = new self();
        $instance->username = $parser->getUsername();
        $instance->image = $parser->getImage();

        return $instance;
    }
}
