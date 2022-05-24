<?php

namespace Jikan\Parser\User;

use Jikan\Helper\Constants;
use Jikan\Helper\Parser;
use Jikan\Model;
use Jikan\Model\User\AnimeStats;
use Jikan\Model\User\MangaStats;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class UsernameByIdParser
 *
 * @package Jikan\Parser
 */
class UsernameByIdParser
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * UsernameByIdParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    public function getUser() : Model\Common\UserMetaBasic
    {
        $node = $this->crawler->filterXPath("//*[@id=\"content\"]/div[1]/div[1]/a");

        preg_match('~(.*?)\'s Profile~', $node->text(), $username);

        return Model\Common\UserMetaBasic::fromMeta(
            $username[1],
            Constants::BASE_URL . $node->attr('href')
        );
    }
}
