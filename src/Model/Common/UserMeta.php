<?php

namespace Jikan\Model\Common;

/**
 * Class UserMeta
 *
 * @package Jikan\Model
 */
class UserMeta extends User
{

    public static function fromMeta($username, $url)
    {
        $instance = new self();

        $instance->username = $username;
        $instance->url = $url;

        return $instance;
    }
}
