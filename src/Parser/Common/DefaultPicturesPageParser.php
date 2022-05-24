<?php

namespace Jikan\Parser\Common;

use Jikan\Model\Common\DefaultPicture;
use Jikan\Model\Common\Picture;
use Jikan\Model\Resource\PersonImageResource\PersonImageResource;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class DefaultPicturesPageParser
 *
 * Character pictures do not have any large image sizes,
 * so this class returns only the default size
 *
 * @package Jikan\Parser\Common
 */
class DefaultPicturesPageParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * DefaultPicturesPageParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return \Jikan\Model\Common\DefaultPicture[]
     * @throws \InvalidArgumentException
     */
    public function getModel(): array
    {
        return $this->crawler
            ->filterXPath('//a[@class="js-picture-gallery"]')
            ->each(
                function (Crawler $crawler) {
                    return PersonImageResource::factory(
                        DefaultPicture::fromParser(new PictureParser($crawler))
                        ->getImageUrl()
                    );
                }
            );
    }
}
