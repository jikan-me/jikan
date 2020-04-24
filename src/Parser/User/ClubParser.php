<?php

namespace Jikan\Parser\User;

use Jikan\Helper\Constants;
use Jikan\Helper\Parser;
use Jikan\Model;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class ClubParser
 *
 * @package Jikan\Parser
 */
class ClubParser
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * ClubParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return array
     * @throws \InvalidArgumentException
     */
    public function getClubs(): array
    {
        $node = $this->crawler
            ->filterXPath('//*[@id="content"]/table/tr/td[2]/ol/li');

        return $node->each(function (Crawler $crawler) {
            return Model\Common\ClubMeta::factory(
                Parser::clubIdFromUrl(
                    $crawler->filterXPath('//a')->attr('href')
                ),
                $crawler->filterXPath('//a')->text(),
                Constants::BASE_URL . $crawler->filterXPath('//a')->attr('href')
            );
        });
    }
}
