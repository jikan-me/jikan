<?php

namespace Jikan\Model;

use Jikan\Parser;

/**
 * Class Profile
 *
 * @package Jikan\Model
 */
class Profile
{

    /**
     * @param Parser\Profile $parser
     *
     * @return Profile
     */
    public static function fromParser(Parser\Profile $parser): self
    {
        $instance = new self();

        return $instance;
    }
}
