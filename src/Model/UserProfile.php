<?php

namespace Jikan\Model;

use Jikan\Parser;

/**
 * Class UserProfile
 *
 * @package Jikan\Model
 */
class UserProfile
{


    /**
     * @param Parser\UserProfile $parser
     *
     * @return UserProfile
     */
    public static function fromParser(Parser\UserProfile $parser): self
    {
        $instance = new self();

        return $instance;
    }
}
