<?php

namespace Jikan\Model\Club;

use Jikan\Parser\Club\UserListParser;

/**
 * Class UserList
 *
 * @package Jikan\Model\Club
 */
class UserList
{
    /**
     * @var UserProfile[]
     */
    private $profiles = [];

    /**
     * @param UserListParser $parser
     *
     * @return UserList
     */
    public static function fromParser(UserListParser $parser): self
    {
        $instance = new self();
        $instance->profiles = $parser->getResults();

        return $instance;
    }
}
