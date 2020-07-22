<?php

namespace Jikan\Model\Common;

use Jikan\Model\Resource\UserImageResource\UserImageResource;

/**
 * Class Reviewer
 *
 * @package Jikan\Model
 */
abstract class Reviewer
{

    /**
     * @var string
     */
    protected $url;

    /**
     * @var UserImageResource
     */
    protected $images;

    /**
     * @var string
     */
    protected $username;
}
