<?php

namespace Jikan\Model\Search;

use Jikan\Model\Resource\UserImageResource\UserImageResource;
use Jikan\Parser;

/**
 * Class UserSearchListItem
 *
 * @package Jikan\Model\Search\Search
 */
class UserSearchListItem
{
    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $url;

    /**
     * @var UserImageResource
     */
    private $images;

    /**
     * @var \DateTimeImmutable
     */
    private $lastOnline;

    /**
     * @param Parser\Search\UserSearchListItemParser $parser
     *
     * @return UserSearchListItem
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    public static function fromParser(Parser\Search\UserSearchListItemParser $parser): self
    {
        $instance = new self();

        $instance->username = $parser->getUsername();
        $instance->url = $parser->getUrl();
        $instance->images = UserImageResource::factory($parser->getImageUrl());
        $instance->lastOnline = $parser->getLastOnline();

        return $instance;
    }


    public static function fromUserSearchParser(Parser\User\Profile\UserProfileParser $parser): self
    {
        $instance = new self();

        $instance->username = $parser->getUsername();
        $instance->url = $parser->getProfileUrl();
        $instance->images = UserImageResource::factory($parser->getImageUrl());
        $instance->lastOnline = $parser->getLastOnline();

        return $instance;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return UserImageResource
     */
    public function getImages(): UserImageResource
    {
        return $this->images;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getLastOnline(): \DateTimeImmutable
    {
        return $this->lastOnline;
    }
}
