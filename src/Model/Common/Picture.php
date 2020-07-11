<?php

namespace Jikan\Model\Common;

use Jikan\Parser\Common\PictureParser;

/**
 * Class Picture
 *
 * @package Jikan\Model
 */
class Picture
{
    /**
     * @var string
     */
    private $large;

    /**
     * @var string
     */
    private $small;


    /**
     * @param PictureParser $parser
     *
     * @return Picture
     * @throws \InvalidArgumentException
     */
    public static function fromParser(PictureParser $parser): Picture
    {
        $instance = new self();
        $instance->large = $parser->getLarge();
        $instance->small = $parser->getSmall();

        return $instance;
    }

    /**
     * @return string
     */
    public function getLarge(): string
    {
        return $this->large;
    }

    /**
     * @return string
     */
    public function getSmall(): string
    {
        return $this->small;
    }
}
