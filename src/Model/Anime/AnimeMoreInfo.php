<?php

namespace Jikan\Model\Anime;

use Jikan\Parser\Anime\MoreInfoParser;

/**
 * Class AnimeMoreInfo
 *
 * @package Jikan\Model\Anime
 */
class AnimeMoreInfo
{
    /**
     * @var string
     */
    private $moreInfo;

    /**
     * @param MoreInfoParser $parser
     *
     * @return AnimeMoreInfo
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
     * @return string|null
     */
    public function getMoreInfo(): ?string
    {
        return $this->moreInfo;
    }
}
