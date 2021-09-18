<?php

namespace Jikan\Parser\Common;

use Jikan\Helper\JString;
use Jikan\Model\Common\MalUrl;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class MalUrlParser
 *
 * @package Jikan\Parser\Common
 */
class MalUrlParser
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * MalUrlParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @param string $url
     *
     * @return int
     */
    public static function parseId(string $url): int
    {
        if (!preg_match_all('#/(\d+)#', $url, $matches)) {
            return 0;
            //            throw new \RuntimeException(sprintf('Unable to parse id from mal url: %s', $url ?? 'null'));
        }

        return (int) $matches[1][0];
    }

    /**
     * @return MalUrl
     * @throws \InvalidArgumentException
     */
    public function getModel(): MalUrl
    {
        $href = $this->crawler->attr('href');
        $href = str_replace('https://myanimelist.net', '', $href);

        return new MalUrl(
            JString::cleanse($this->crawler->text()),
            'https://myanimelist.net'.$href
        );
    }
}
