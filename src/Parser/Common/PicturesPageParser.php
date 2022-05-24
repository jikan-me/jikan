<?php

namespace Jikan\Parser\Common;

use Jikan\Model\Common\Picture;
use Jikan\Model\Resource\AnimeImageResource\AnimePicturesImageResource;
use Jikan\Model\Resource\CommonImageResource\CommonImageResource;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class PicturePageParser
 *
 * @package Jikan\Parser\Common
 */
class PicturesPageParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * AnimeParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return \Jikan\Model\Common\Picture[]
     * @throws \InvalidArgumentException
     */
    public function getModel(): array
    {
        return $this->crawler
            ->filterXPath('//a[@class="js-picture-gallery"]')
            ->each(
                function (Crawler $crawler) {
                    return CommonImageResource::factory(
                        Picture::fromParser(new PictureParser($crawler))
                            ->getImageUrl()
                    );
                }
            );
    }
}
