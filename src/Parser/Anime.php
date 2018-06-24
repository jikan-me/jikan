<?php

namespace Jikan\Parser;

use Symfony\Component\DomCrawler\Crawler;
use Jikan\Helper\JString;

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
        return \Jikan\Model\Anime::fromParser($this);
    }

    /**
     * @return string
     */
    public function getAnimeTitle():string
    {
        return $this->crawler->filterXPath('//meta[@property=\'og:title\']')->extract(['content'])[0];
    }

    /**
     * @return string
     */
    public function getAnimeURL():string
    {
        return $this->crawler->filterXPath('//meta[@property=\'og:url\']')->extract(['content'])[0];
    }

    /**
     * @return string
     */
    public function getAnimeImageURL():string
    {
        return $this->crawler->filterXPath('//meta[@property=\'og:image\']')->extract(['content'])[0];
    }

    /**
     * @return string
     */
    public function getAnimeSynopsis():string
    {
        return JString::cleanse($this->crawler->filterXPath('//meta[@property=\'og:description\']')->extract(['content'])[0]);
    }

    /**
     * @return string
     */
    public function getAnimeTitleEnglish():string
    {
        //var_dump($this->crawler->filterXPath('//*[@id="content"]/table/tbody/tr/td[1]/div/div[6]/text()')->extract(['content']));
        return ""; // whitebox
    }
}
