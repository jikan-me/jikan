<?php

namespace Jikan\Model\Common;

/**
 * Class User
 *
 * @package Jikan\Model
 */
abstract class User
{
    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $username;

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
