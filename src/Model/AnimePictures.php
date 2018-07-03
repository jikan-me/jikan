<?php

namespace Jikan\Model;

use Jikan\Parser\Anime\AnimePicturesParser;

/**
 * Class AnimePictures
 *
 * @package Jikan\Model
 */
class AnimePictures
{
    /**
     * @var string[]
     */
    private $pictures = [];

    /**
     * @param AnimePicturesParser $parser
     *
     * @return AnimePictures
     */
    public static function fromParser(AnimePicturesParser $parser): AnimePictures
    {
        $instance = new self();
        $instance->pictures = $parser->getPictures();

        return $instance;
    }

    /**
     * @return string[]
     */
    public function getPictures(): array
    {
        return $this->pictures;
    }
}
