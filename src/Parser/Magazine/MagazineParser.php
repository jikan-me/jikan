<?php

namespace Jikan\Parser\Magazine;

use Jikan\Helper\JString;
use Jikan\Helper\Parser;
use Jikan\Model;
use Jikan\Parser\Common\MangaCardParser;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class MagazineParser
 *
 * @package Jikan\Parser
 */
class MagazineParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * MagazineParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return \Jikan\Model\Magazine\Magazine
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getModel(): Model\Magazine\Magazine
    {
        return Model\Magazine\Magazine::fromParser($this);
    }

    /**
     * @return array|\Jikan\Model\Magazine\MagazineManga[]
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getResults(): array
    {
        return $this->crawler
            ->filter('div.seasonal-anime')
            ->each(
                function (Crawler $MangaCrawler) {
                    return (new MangaCardParser($MangaCrawler))->getModel();
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
