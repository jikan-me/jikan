<?php

namespace Jikan\Parser\Producer;

use Jikan\Helper\JString;
use Jikan\Helper\Parser;
use Jikan\Model;
use Jikan\Parser\Common\AnimeCardParser;
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
    private $crawler;

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
            ->filterXPath('//div[contains(@class, "js-categories-seasonal")]')
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
        return new Model\Common\MalUrl(
            JString::cleanse(
                Parser::removeChildNodes($this->crawler->filterXPath('//span[@class=\'di-ib mt4\']'))->text()
            ),
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
}
