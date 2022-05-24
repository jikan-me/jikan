<?php

namespace Jikan\Parser\Common;

use Jikan\Helper\Constants;
use Jikan\Helper\Parser;
use Jikan\Model\Common\CommonMeta;
use Jikan\Model\Common\ItemMeta;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class Recommendation
 *
 * @package Jikan\Parser\Common
 */
class Recommendation implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * Recommendation constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getUrl(): string
    {
        return $this->crawler
            ->filterXPath('//table/tr/td[2]/div[2]/a[1]')->attr('href');
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getImageUrl(): string
    {
        return Parser::parseImageQuality(
            $this->crawler
                ->filterXPath('//table/tr/td[1]/div[1]/a/img')->attr('data-src')
        );
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getRecommendationurl(): string
    {
        return Constants::BASE_URL . $this->crawler
            ->filterXPath('//table/tr/td[2]/div[2]/span/a')->attr('href');
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getTitle(): string
    {
        return $this->crawler
            ->filterXPath('//table/tr/td[2]/div[2]/a[1]')->text();
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getRecommendationCount(): int
    {
        $node = $this->crawler
            ->filterXPath('//table/tr/td[2]/div[4]/a[1]/strong');

        if (!$node->count()) {
            return 1;
        }

        return ((int) $node->text()) + 1;
    }

    /**
     * @return \Jikan\Model\Common\Recommendation
     * @throws \InvalidArgumentException
     */
    public function getModel(): \Jikan\Model\Common\Recommendation
    {
        return \Jikan\Model\Common\Recommendation::fromParser($this);
    }

    public function getEntryMeta() : CommonMeta
    {
        return new CommonMeta(
            $this->getTitle(),
            $this->getUrl(),
            $this->getImageUrl()
        );
    }
}
