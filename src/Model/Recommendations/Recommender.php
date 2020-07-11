<?php

namespace Jikan\Model\Recommendations;

use Jikan\Model\Common\User;

/**
 * Class Recommender
 *
 * @package Jikan\Model
 */
class Recommender extends User
{
    public static function fromMeta($url, $username): self
    {
        $instance = new self();

        $instance->url = $url;
        $instance->username = $username;

        return $instance;
    }
}
