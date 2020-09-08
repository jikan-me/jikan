<?php

namespace Jikan\Model\Common;

use Jikan\Parser\Character\OgraphyParser;

/**
 * Class Ography
 *
 * @package Jikan\Model
 */
abstract class Ography
{
    /**
     * @var string
     */
    protected $role;

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }
}
