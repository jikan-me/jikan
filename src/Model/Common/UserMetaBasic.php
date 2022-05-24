<?php

namespace Jikan\Model\Common;

/**
 * Class UserMetaBasic
 *
 * @package Jikan\Model
 */
class UserMetaBasic extends User
{
    public static function fromMeta($username, $url)
    {
        $instance = new self();

        $instance->username = $username;
        $instance->url = $url;

        return $instance;
    }
}
