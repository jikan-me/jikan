<?php

namespace Jikan\Model\User;

use Jikan\Model\Common\UserMeta;
use Jikan\Model\Resource\UserImageResource\UserImageResource;
use Jikan\Parser\User\Friends\FriendParser;

/**
 * Class Friend
 *
 * @package Jikan\Model
 */
class Friend
{
    /**
     * @var UserMeta
     */
    private $user;

    /**
     * @var \DateTimeImmutable
     */
    private $lastOnline;

    /**
     * @var \DateTimeImmutable
     */
    private $friendsSince;

    /**
     * @param FriendParser $parser
     *
     * @return Friend
     * @throws \Exception
     */
    public static function fromParser(FriendParser $parser): Friend
    {
        $instance = new self();
        $instance->user = $parser->getUserMeta();
        $instance->friendsSince = $parser->getFriendsSince();
        $instance->lastOnline = $parser->getLastOnline();

        return $instance;
    }

    /**
     * @return UserMeta
     */
    public function getUser(): UserMeta
    {
        return $this->user;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getLastOnline(): \DateTimeImmutable
    {
        return $this->lastOnline;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getFriendsSince(): \DateTimeImmutable
    {
        return $this->friendsSince;
    }
}
