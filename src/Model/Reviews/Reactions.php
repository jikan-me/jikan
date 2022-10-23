<?php

namespace Jikan\Model\Reviews;

use Jikan\Parser;

/**
 * Class Reactions
 *
 * @package Jikan\Model
 */
class Reactions
{
    /**
     * @var int
     */
    private int $overall;

    /**
     * @var int
     */
    private int $nice;

    /**
     * @var int
     */
    private int $loveIt;

    /**
     * @var int
     */
    private int $funny;

    /**
     * @var int
     */
    private int $confusing;

    /**
     * @var int
     */
    private int $informative;

    /**
     * @var int
     */
    private int $wellWritten;

    /**
     * @var int
     */
    private int $creative;

    /**
     * @param Parser\Reviews\ReactionsParser $parser
     *
     * @return Reactions
     * @throws \InvalidArgumentException
     */
    public static function fromParser(Parser\Reviews\ReactionsParser $parser): Reactions
    {
        $instance = new self();

        $instance->overall = $parser->getOverall();
        $instance->loveIt = $parser->getLoveIt();
        $instance->nice = $parser->getNice();
        $instance->funny = $parser->getFunny();
        $instance->confusing = $parser->getConfusing();
        $instance->informative = $parser->getInformative();
        $instance->wellWritten = $parser->getWellWritten();
        $instance->creative = $parser->getCreative();

        return $instance;
    }
}
