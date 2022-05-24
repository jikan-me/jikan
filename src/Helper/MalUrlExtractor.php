<?php

namespace Jikan\Helper;

use Jikan\Parser\Common\MalUrlParser;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class MalUrlExtractor
 *
 * @package Jikan\Parser
 */
class MalUrlExtractor
{
    public const TYPE_ANIME = 'anime';
    public const TYPE_MANGA = 'manga';

    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * @var string
     */
    private $type;

    /**
     * @var bool
     */
    private $imageLinks;

    /**
     * MalUrlExtractor constructor.
     *
     * @param Crawler $crawler
     * @param string  $type
     * @param bool    $imageLinks
     */
    public function __construct(Crawler $crawler, string $type, bool $imageLinks = false)
    {
        $this->crawler = clone $crawler;
        $this->type = $type;
        $this->imageLinks = $imageLinks;
    }

    /**
     * Extract all mal urls that begin with BASE_URL/$type
     *
     * @return \Jikan\Model\Common\MalUrl[]
     * @throws \InvalidArgumentException
     */
    public function getMalUrls(): array
    {
        if (!$this->imageLinks) {
            $this->crawler->filterXPath('//a/img')->each(
                function (Crawler $c) {
                    $node = $c->ancestors()->first()->getNode(0);
                    /**
                *
                     *
                * @noinspection NullPointerExceptionInspection
                */
                    $node->parentNode->removeChild($node);
                }
            );
        }

        $xpath = sprintf('//a[contains(@href, "%s/%s")]', Constants::BASE_URL, $this->type);

        return $this->crawler
            ->filterXPath($xpath)
            ->each(
                function (Crawler $c) {
                    return (new MalUrlParser($c))->getModel();
                }
            );
    }
}
