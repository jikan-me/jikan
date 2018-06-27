<?php

namespace Jikan\Parser;

use Jikan\Helper\JString;
use Jikan\Helper\Parser;
use Jikan\Model;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class Person
 *
 * @package Jikan\Parser
 */
class Person implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * Person constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * Return the model
     */
    public function getModel(): Model\Person
    {
        return Model\Person::fromParser($this);
    }


    /**
     * @return int
     * @throws \RuntimeException
     */
    public function getPersonId(): int
    {
        preg_match('#https?://myanimelist.net/Person/(\d+)#', $this->getPersonURL(), $matches);

        return (int)$matches[1];
    }

    /**
     * @return string
     */
    public function getPersonURL(): string
    {
        return $this->crawler->filterXPath('//meta[@property=\'og:url\']')->attr('content');
    }

    /**
     * @return string
     */
    public function getPersonTitle(): string
    {
        return $this->crawler->filterXPath('//meta[@property=\'og:title\']')->attr('content');
    }

    /**
     * @return string
     */
    public function getPersonImageURL(): string
    {
        return $this->crawler->filterXPath('//meta[@property=\'og:image\']')->attr('content');
    }

    /**
     * @return string
     */
    public function getPersonSynopsis(): string
    {
        return JString::cleanse(
            $this->crawler->filterXPath('//meta[@property=\'og:description\']')->attr('content')
        );
    }

}
