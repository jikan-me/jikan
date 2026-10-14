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
     * The entries behind a list of related-entry links. MyAnimeList sometimes lists an entry as an empty link
     * (<a href="..."></a>, for example a removed entry); those are not entries and are skipped.
     *
     * @return list<MalUrl>
     */
    public static function fromLinks(Crawler $links): array
    {
        $urls = [];
        foreach ($links as $link) {
            if (JString::cleanse($link->textContent) !== '') {
                $urls[] = new self(new Crawler($link, $links->getUri()))->getModel();
            }
        }

        return $urls;
    }

    /**
     * @param string $url
     *
     * @return int|string
     */
    public static function parseId(string $url): int|string
    {
        if (preg_match_all('#/(\d+)#', $url, $matches)) {
            return (int) $matches[1][0];
        }

        if (preg_match_all('#featured/tag/(\w+)#', $url, $matches)) {
            return (string) $matches[1][0];
        }

        return 0;
        //  throw new \RuntimeException(sprintf('Unable to parse id from mal url: %s', $url ?? 'null'));
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
            'https://myanimelist.net' . $href
        );
    }
}
