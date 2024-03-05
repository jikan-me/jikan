<?php

namespace Jikan\Parser\Common;

use Jikan\Helper\JString;
use Jikan\Model\Common\MalUrl;
use Jikan\Model\Common\TagUrl;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class TagUrlParser
 *
 * @package Jikan\Parser\Common
 */
class TagUrlParser
{
    /**
     * @var Crawler
     */
    private Crawler $crawler;

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
     * @return TagUrl
     * @throws \InvalidArgumentException
     */
    public function getModel(): TagUrl
    {
        $href = $this->crawler->attr('href');
        $urlParts = explode("/", parse_url($href)['path']);

        return new TagUrl(
            $urlParts[array_key_last($urlParts)],
            JString::cleanse($this->crawler->text()),
            $href
        );
    }
}
