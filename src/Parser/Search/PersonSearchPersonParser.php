<?php

namespace Jikan\Parser\Search;

use Jikan\Helper\Constants;
use Jikan\Helper\JString;
use Jikan\Helper\Parser;
use Jikan\Model\Common\MalUrl;
use Jikan\Model\Search\PersonSearchListItem;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class PersonSearchListItemParser
 *
 * @package Jikan\Parser
 */
class PersonSearchPersonParser
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
        return PersonSearchListItem::fromPersonParser($this);
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getUrl(): string
    {
        return $this->crawler->filterXPath('//meta[@property=\'og:url\']')->attr('content');
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getName(): string
    {
        return JString::cleanse($this->crawler->filterXPath('//meta[@property=\'og:title\']')->attr('content'));
    }

    /**
     * @return array
     * @throws \InvalidArgumentException
     */
    public function getAlternativeNames(): array
    {
        $node = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]/div/span[text()="Alternate names:"]');

        if (!$node->count()) {
            return [];
        }

        $names = explode(
            ',',
            str_replace($node->text(), '', $node->parents()->text())
        );

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
        return $this->crawler->filterXPath('//meta[@property=\'og:image\']')->attr('content');
    }
}
