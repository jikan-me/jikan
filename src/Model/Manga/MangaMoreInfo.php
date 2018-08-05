<?php

namespace Jikan\Model\Manga;

use Jikan\Parser\Manga\MoreInfoParser;

/**
 * Class MangaMoreInfo
 *
 * @package Jikan\Model\Manga
 */
class MangaMoreInfo
{
    /**
     * @var string
     */
    private $moreInfo;

    /**
     * @param MoreInfoParser $parser
     *
     * @return MangaMoreInfo
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
    public function getMoreInfo(): ?string
    {
        return $this->moreInfo;
    }
}
