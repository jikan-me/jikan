<?php

namespace Jikan\Model\Common;

use Jikan\Parser\Common\PictureParser;

/**
 * Class DefaultPicture
 *
 * @package Jikan\Model
 */
class DefaultPicture
{
    /**
     * @var string
     */
    private $imageUrl;

    /**
     * @param PictureParser $parser
     *
     * @return DefaultPicture
     * @throws \InvalidArgumentException
     */
    public static function fromParser(PictureParser $parser): DefaultPicture
    {
        $instance = new self();
        $instance->imageUrl = $parser->getSmall();

        return $instance;
    }

    /**
     * @return string
     */
    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }
}
