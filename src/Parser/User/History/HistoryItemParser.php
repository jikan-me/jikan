<?php

namespace Jikan\Parser\User\History;

use Jikan\Exception\ParserException;
use Jikan\Helper\Constants;
use Jikan\Helper\JString;
use Jikan\Helper\Parser;
use Jikan\Model\Common\MalUrl;
use Jikan\Model\User\History;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class HistoryItemParser
 *
 * @package Jikan\Parser\HistoryItem
 */
class HistoryItemParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * HistoryItemParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * Return the model
     *
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    public function getModel(): History
    {
        return History::fromParser($this);
    }

    /**
     * @return MalUrl
     * @throws \InvalidArgumentException
     */
    public function getUrl(): MalUrl
    {
        $url = $this->crawler->filterXPath('//td[1]/a')->attr('href');
        $name = JString::cleanse(
            $this->crawler->filterXPath('//td[1]/a')->text()
        );

        preg_match('~/(.\w+).php\?id=(\d+)~', $url, $matches);

        if (empty($matches)) {
            throw new ParserException('Could not parse MalUrl');
        }

        $url = Constants::BASE_URL.'/'.$matches[1].'/'.$matches[2];

        return new MalUrl($name, $url);
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getIncrement(): int
    {
        return (int)$this->crawler->filterXPath('//td[1]/strong')->text();
    }

    /**
     * @return \DateTimeImmutable
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    public function getDate(): \DateTimeImmutable
    {
        $date = JString::UTF8NbspTrim(
            JString::cleanse(
                Parser::removeChildNodes(
                    $this->crawler->filterXPath('//td[2]')
                )->text()
            )
        );

        return new \DateTimeImmutable($date, new \DateTimeZone('UTC'));
    }
}
