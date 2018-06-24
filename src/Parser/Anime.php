<?php

namespace Jikan\Parser;

use Jikan\Helper\JString;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class Anime
 *
 * @package Jikan\Parser
 */
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
    public function getAnimeTitle(): string
    {
        return $this->crawler->filterXPath('//meta[@property=\'og:title\']')->extract(['content'])[0];
    }

    /**
     * @return string
     */
    public function getAnimeURL(): string
    {
        return $this->crawler->filterXPath('//meta[@property=\'og:url\']')->extract(['content'])[0];
    }

    /**
     * @return string
     */
    public function getAnimeImageURL(): string
    {
        return $this->crawler->filterXPath('//meta[@property=\'og:image\']')->extract(['content'])[0];
    }

    /**
     * @return string
     */
    public function getAnimeSynopsis(): string
    {
        return JString::cleanse(
            $this->crawler->filterXPath('//meta[@property=\'og:description\']')->extract(['content'])[0]
        );
    }

    /**
     * @return string
     */
    public function getAnimeTitleEnglish(): string
    {
        $title = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
            ->filterXPath('//span[text()="English:"]');
            
        if ($title->count() > 0) {
            return JString::cleanse(
                str_replace($title->text(), '', $title->parents()->text())
            );
        }
    }

    /**
     * @return string
     */
    public function getAnimeTitleSynonyms(): string
    {
        $title = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
            ->filterXPath('//span[text()="Synonyms:"]');
            
        if ($title->count() > 0) {
            return JString::cleanse(
                str_replace($title->text(), '', $title->parents()->text())
            );
        }
    }

    /**
     * @return string
     */
    public function getAnimeTitleJapanese(): string
    {
        $title = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
            ->filterXPath('//span[text()="Japanese:"]');
            
        if ($title->count() > 0) {
            return JString::cleanse(
                str_replace($title->text(), '', $title->parents()->text())
            );
        }
    }

    /**
     * @return string
     */
    public function getAnimeType(): string
    {
        $title = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
            ->filterXPath('//span[text()="Type:"]');
            
        if ($title->count() > 0) {
            return JString::cleanse(
                str_replace($title->text(), '', $title->parents()->text())
            );
        }
    }

    /**
     * @return int
     */
    public function getAnimeEpisodes(): int
    {
        $title = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
            ->filterXPath('//span[text()="Episodes:"]');
            
        if ($title->count() > 0) {
            return (str_replace($title->text(), '', $title->parents()->text()) == 'Unknown') ? 0 : (int) str_replace($title->text(), '', $title->parents()->text());
        }
    }

    /**
     * @return bool
     */
    public function getAnimeEpisodesUnknown(): bool
    {
        return true;
    }

    /**
     * @return string
     */
    public function getAnimeStatus(): string
    {
        $title = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
            ->filterXPath('//span[text()="Status:"]');
            
        if ($title->count() > 0) {
            return JString::cleanse(
                str_replace($title->text(), '', $title->parents()->text())
            );
        }
    }

    /**
     * @return bool
     */
    public function getAnimeAiring(): bool
    {
        return true;
    }
}
