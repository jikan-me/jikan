<?php

namespace Jikan\Parser\Search;

use Jikan\Helper\Constants;
use Jikan\Helper\JString;
use Jikan\Helper\Parser;
use Jikan\Model\Search\PersonSearchListItem;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class PersonSearchListItemParser
 *
 * @package Jikan\Parser
 */
class PersonSearchListItemParser
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * PersonSearchParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return PersonSearchListItem
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getModel(): PersonSearchListItem
    {
        return PersonSearchListItem::fromParser($this);
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getUrl(): string
    {
        return Constants::BASE_URL . $this->crawler->filterXPath('//td[2]/a')->attr('href');
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getName(): string
    {
        return $this->crawler->filterXPath('//td[2]/a')->text();
    }

    /**
     * @return array
     * @throws \InvalidArgumentException
     */
    public function getAlternativeNames(): array
    {
        $names = $this->crawler->filterXPath('//td[2]/small');

        if (!$names->count()) {
            return [];
        }

        $names = str_replace(['(', ')'], '', $names->text());
        $names = explode(',', $names);

        foreach ($names as &$name) {
            $name = JString::cleanse($name);
        }

        return $names;
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getImageUrl(): string
    {
        return Parser::parseImageQuality(
            $this->crawler->filterXPath('//td[1]/div/a/img')->attr('data-src')
        );
    }
}
