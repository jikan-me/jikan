<?php

namespace Jikan\Parser;

use Jikan\Helper\JString;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class Anime
 *
 * @package Jikan\Parser
 */
class Anime implements ParserInterface
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
     * @return string|null
     * @throws \InvalidArgumentException
     */
    public function getAnimeTitleEnglish(): ?string
    {
        $title = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
            ->filterXPath('//span[text()="English:"]');
        if (!$title->count()) {
            return null;
        }

        return JString::cleanse(
            str_replace($title->text(), '', $title->parents()->text())
        );

    }

    /**
     * @return string|null
     * @throws \InvalidArgumentException
     */
    public function getAnimeTitleSynonyms(): ?string
    {
        $title = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
            ->filterXPath('//span[text()="Synonyms:"]');
        if (!$title->count()) {
            return null;
        }

        return JString::cleanse(
            str_replace($title->text(), '', $title->parents()->text())
        );

    }

    /**
     * @return string|null
     * @throws \InvalidArgumentException
     */
    public function getAnimeTitleJapanese(): ?string
    {
        $title = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
            ->filterXPath('//span[text()="Japanese:"]');
        if (!$title->count()) {
            return null;
        }

        return JString::cleanse(
            str_replace($title->text(), '', $title->parents()->text())
        );
    }

    /**
     * @return string|null
     * @throws \InvalidArgumentException
     */
    public function getAnimeType(): ?string
    {
        $type = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
            ->filterXPath('//span[text()="Type:"]');
        if (!$type->count()) {
            return null;
        }

        return JString::cleanse(
            str_replace($type->text(), '', $type->parents()->text())
        );
    }

    /**
     * @return int|null
     * @throws \InvalidArgumentException
     */
    public function getAnimeEpisodes(): ?int
    {
        $episodes = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
            ->filterXPath('//span[text()="Episodes:"]');

        if (!$episodes->count()) {
            return null;
        }

        return (str_replace($episodes->text(), '', $episodes->parents()->text()) === 'Unknown') ? 0 : (int)str_replace(
            $episodes->text(),
            '',
            $episodes->parents()->text()
        );
    }

    /**
     * @return string|null
     * @throws \InvalidArgumentException
     */
    public function getAnimeStatus(): ?string
    {
        $status = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
            ->filterXPath('//span[text()="Status:"]');
        if (!$status->count()) {
            return null;
        }

        return JString::cleanse(
            str_replace($status->text(), '', $status->parents()->text())
        );
    }

    /**
     * @return string
     */
    public function getAnimeAiredString(): string
    {
        $aired = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
            ->filterXPath('//span[text()="Aired:"]');
            
        if ($aired->count() > 0) {
            return JString::cleanse(
                str_replace($aired->text(), '', $aired->parents()->text())
            );
        }
    }

    /**
     * @return string
     */
    public function getAnimePremiered(): string
    {
        $aired = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
            ->filterXPath('//span[text()="Premiered:"]');
            
        if ($aired->count() > 0) {
            return JString::cleanse(
                str_replace($aired->text(), '', $aired->parents()->text())
            );
        }
    }

    /**
     * @return string
     */
    public function getAnimeBroadcast(): string
    {
        $broadcast = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
            ->filterXPath('//span[text()="Broadcast:"]');
            
        if ($broadcast->count() > 0) {
            return JString::cleanse(
                str_replace($broadcast->text(), '', $broadcast->parents()->text())
            );
        }
    }

    /**
     * @return array
     */
    public function getAnimeProducer(): array
    {
        $producer = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
            ->filterXPath('//span[text()="Producers:"]');

        $producers = [];
        if (strpos($producer->parents()->text(), 'None found') === false && $producer->count() > 0) {
            $producers = $producer->parents()->first()->filter('a')->each(function($node) {
                return [
                    'url' => BASE_URL . substr($node->attr('href'), 1),
                    'name' => $node->text()
                ];
            });
        }
        return $producers;
    }
}
