<?php

namespace Jikan\Parser\Anime;

use Jikan\Model\AnimePictures;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class AnimePicturesParser
 * @package Jikan\Parser\Anime
 */
class AnimePicturesParser implements ParserInterface
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

    public function getPictures(): array
    {
        $pictureLinks = $this->crawler
            ->filterXPath('//a[@class="js-picture-gallery"]');

        $pictures = [];

        foreach ($pictureLinks as $pictureLink) {
            $pictures[] = $pictureLink->getAttribute('href');
        }

        return $pictures;
    }

    /**
     * @return AnimePictures
     */
    public function getModel(): AnimePictures
    {
        return AnimePictures::fromParser($this);
    }
}
