<?php

namespace Jikan\Model\Anime;

use Jikan\Parser\Anime\MoreInfoParser;

/**
 * Class MoreInfo
 *
 * @package Jikan\Model\Anime
 */
class MoreInfo
{
    /**
     * @var string
     */
    private $moreInfo;
    /**
     * @param MoreInfoParser $parser
     *
     * @return MoreInfo
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public static function fromParser(MoreInfoParser $parser): self
    {
        $instance = new self();
        $instance->moreInfo = $parser->getMoreInfo();
        return $instance;
    }
    /**
     * @return string
     */
    public function getMoreInfo(): string
    {
        return $this->moreInfo;
    }
}