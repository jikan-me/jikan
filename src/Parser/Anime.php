<?php

namespace Jikan\Parser;

use Symfony\Component\DomCrawler\Crawler;

class Anime
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * Anime constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return \Jikan\Model\Anime
     */
    public function getModel(): \Jikan\Model\Anime
    {
//        $model->set(
//            'Anime',
//            'title',
//            $this->crawler->filterXPath('//meta[@property=\'og:title\']')->extract(['content'])[0]
//        );
//        $model->set(
//            'Anime',
//            'image_url',
//            $this->crawler->filterXPath('//meta[@property=\'og:image\']')->extract(['content'])[0]
//        );
//        $model->set(
//            'Anime',
//            'link_canonical',
//            $this->crawler->filterXPath('//meta[@property=\'og:url\']')->extract(['content'])[0]
//        );
//        $model->set(
//            'Anime',
//            'synopsis',
//            $this->crawler->filterXPath('//meta[@property=\'og:description\']')->extract(['content'])[0]
//        );

        return \Jikan\Model\Anime::fromParser($this);
    }

    /**
     * @return string
     */
    public function getAnimeTitle():string
    {
        return $this->crawler->filterXPath('//meta[@property=\'og:title\']')->extract(['content'])[0];
    }
}
