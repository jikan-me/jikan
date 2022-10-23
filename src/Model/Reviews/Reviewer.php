<?php

namespace Jikan\Model\Reviews;

use Jikan\Model\Common\User;
use Jikan\Model\Resource\UserImageResource\UserImageResource;
use Jikan\Parser\Reviews\ReviewerParser;

/**
 * Class Reviewer
 *
 * @package Jikan\Model
 */
class Reviewer extends User
{
    /**
     * @var UserImageResource
     */
    protected UserImageResource $images;


    public static function fromParser(ReviewerParser $parser): self
    {
        $instance = new self();

        $instance->url = $parser->getUrl();
        $instance->images = UserImageResource::factory($parser->getImageUrl());
        $instance->username = $parser->getUsername();

        return $instance;
    }

    /**
     * @return UserImageResource
     */
    public function getImages(): UserImageResource
    {
        return $this->images;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }
}
