<?php

namespace Jikan\Parser\Producer;

use Jikan\Helper\JString;
use Jikan\Helper\Parser;
use Jikan\Model;
use Jikan\Parser\Common\AnimeCardParser;
use Jikan\Parser\Common\UrlParser;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class ProducerParser
 *
 * @package Jikan\Parser
 */
class ProducerParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private Crawler $crawler;

    /**
     * ProducerParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return \Jikan\Model\Producer\Producer
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getModel(): Model\Producer\Producer
    {
        return Model\Producer\Producer::fromParser($this);
    }

    /**
     * @return array|\Jikan\Model\Producer\ProducerAnime[]
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getResults(): array
    {
        return $this->crawler
            ->filterXPath('//*[@id="content"]/div[2]/div[contains(@class, "js-categories-seasonal")]/div[contains(@class, "seasonal-anime")]')
            ->each(
                function (Crawler $animeCrawler) {
                    return (new AnimeCardParser($animeCrawler))->getModel();
                }
            );
    }

    /**
     * @return \Jikan\Model\Common\MalUrl
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getUrl(): Model\Common\MalUrl
    {
        $title = $this->crawler->filterXPath('//*[@id="contentWrapper"]/div[1]/h1');
        return new Model\Common\MalUrl(
            $title->text(),
            $this->crawler->filterXPath('//meta[@property="og:url"]')->attr('content')
        );
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getLastPage(): int
    {
        $pages = $this->crawler
            ->filterXPath('//*[@id="content"]/div[5]/div/a[contains(@class, "link")]');

        if (!$pages->count()) {
            return 1;
        }

        $pages = $pages
            ->nextAll()
            ->last();

        if (!$pages->count()) {
            return 1;
        }

        preg_match('~\?page=(\d+)$~', $pages->attr('href'), $page);

        return $page[1];
    }

    /**
     * @return bool
     */
    public function getHasNextPage(): bool
    {
        $pages = $this->crawler
            ->filterXPath('//*[@id="content"]/div[5]/div/a[contains(@class, "link")]');

        if (!$pages->count()) {
            return false;
        }

        $pages = $pages
            ->nextAll()
            ->last()
            ->filterXPath('//a[not(contains(@class, "current"))]');

        if (!$pages->count()) {
            return false;
        }

        return true;
    }

    /**
     * @return array
     */
    public function getTitles(): array
    {
        $titles = [];
        $titleEnglish = $this->crawler->filterXPath('//*[@id="contentWrapper"]/div[1]/h1');

        if ($titleEnglish->count()) {
            $titles[] = new Model\Common\Title(Model\Common\Title::TYPE_DEFAULT, $titleEnglish->text());
        }

        $titleJapanese = $this->crawler->filterXPath('//span[text()="Japanese:"]');

        if ($titleJapanese->count()) {
            $titles[] = new Model\Common\Title(
                'Japanese',
                JString::cleanse(
                    str_replace($titleJapanese->text(), '', $titleJapanese->ancestors()->text())
                )
            );
        }

        return $titles;
    }

    /**
     * @return \DateTimeImmutable|null
     * @throws \Exception
     */
    public function getEstablished(): ?\DateTimeImmutable
    {
        $node = $this->crawler
            ->filterXPath('//span[text()="Established:"]');

        if (!$node->count()) {
            return null;
        }

        return Parser::parseDateMDYReadable(
            JString::cleanse(
                str_replace($node->text(), '', $node->ancestors()->text())
            )
        );
    }

    /**
     * @return string|null
     */
    public function getAbout(): ?string
    {
        // it will be the node without <span class="dark_text">
        $node = $this->crawler
            ->filterXPath('//*[@id="content"]/div[1]//div[contains(@class, "spaceit_pad")]/span[not(contains(@class, "dark_text"))]');

        if (!$node->count()) {
            return null;
        }

        return JString::cleanse(
            $node->html()
        );
    }

    /**
     * @return int|null
     */
    public function getFavorites(): ?int
    {
        $favorite = $this->crawler
            ->filterXPath('//span[text()="Member Favorites:"]');

        if (!$favorite->count()) {
            return null;
        }

        return JString::cleanse(
            str_replace([$favorite->text(), ','], '', $favorite->ancestors()->text())
        );
    }

    /**
     * @return int
     */
    public function getAnimeCount(): int
    {
        $node = $this->crawler->filterXPath('//*[@id="content"]/div[2]/div[contains(@class, "navi-seasonal")]/div/ul/li[1]');

        preg_match('~\((.*)\)~', $node->text(), $matches);

        return JString::cleanse(
            str_replace(',', '', $matches[1])
        );
    }


    /**
     * @return array
     */
    public function getExternalLinks(): array
    {
        $links = $this->crawler
            ->filterXPath('//*[@id="content"]/div[1]/div[contains(@class, "user-profile-sns")]/span//a');

        if (!$links->count()) {
            return [];
        }

        return $links->each(function (Crawler  $c) {
            return (new UrlParser($c))->getModel();
        });
    }

    /**
     * @return Model\Resource\WrapImageResource\WrapImageResource
     */
    public function getImages(): Model\Resource\WrapImageResource\WrapImageResource
    {
        return Model\Resource\WrapImageResource\WrapImageResource::factory(
            $this->crawler
                ->filterXPath('//*[@id="content"]/div[1]/div[contains(@class, "logo")]/img')
                ->attr('data-src')
        );
    }
}
