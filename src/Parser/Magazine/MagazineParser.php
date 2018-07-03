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
     * @return Model\Magazine
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getModel(): Model\Magazine
    {
        return Model\Magazine::fromParser($this);
    }

    /**
     * @return array|Model\MagazineManga[]
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getMagazineManga(): array
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
     * @return Model\MalUrl
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getUrl(): Model\MalUrl
    {
        return new Model\MalUrl(
            JString::cleanse(
                Parser::removeChildNodes($this->crawler->filterXPath('//span[@class=\'di-ib mt4\']'))->text()
            ),
            $this->crawler->filterXPath('//meta[@property="og:url"]')->attr('content')
        );
    }
}
