<?php

namespace Jikan\Parser\Anime;

use Jikan\Helper\JString;
use Jikan\Helper\Parser;
use Jikan\Model\Anime\AnimeEpisode;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class AnimeEpisodeParser
 *
 * @package Jikan\Parser\AnimeEpisodeParser
 */
class AnimeEpisodeParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * EpisodeListItemParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return AnimeEpisode
     * @throws \Exception
     */
    public function getModel(): AnimeEpisode
    {
        return AnimeEpisode::fromParser($this);
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getEpisodeId(): int
    {
        return (int) trim(
            str_replace(
                ['-', '#'],
                '',
                $this->crawler->filterXPath('//h2[contains(@class, \'fs18\')]/span')->text()
            )
        );
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getEpisodeUrl(): string
    {
        return $this->crawler->filterXPath('//meta[@property=\'og:url\']')->attr('content');
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getTitle(): string
    {
        return JString::cleanse(
            Parser::removeChildNodes(
                $this->crawler->filterXPath('//h2[contains(@class, \'fs18\')]')
            )->text()
        );
    }

    /**
     * @return null|string
     * @throws \InvalidArgumentException
     */
    public function getTitleJapanese(): ?string
    {
        $node = $this->crawler->filterXPath('//p[contains(@class, \'fn-grey2\')]');

        if (!$node->count()) {
            return null;
        }

        preg_match('~(.*)\((.*)\)~', $node->text(), $matches);

        return (!empty($matches[2]) ? JString::cleanse($matches[2]) : null);
    }

    /**
     * @return null|string
     * @throws \InvalidArgumentException
     */
    public function getTitleRomanji(): ?string
    {
        $node = $this->crawler->filterXPath('//p[contains(@class, \'fn-grey2\')]');

        if (!$node->count()) {
            return null;
        }

        preg_match('~(.*)\((.*)\)~', $node->text(), $matches);

        return (!empty($matches[1]) ? JString::cleanse($matches[1]) : null);
    }


    /**
     * @return \DateTimeImmutable|null
     * @throws \Exception
     */
    public function getAired(): ?\DateTimeImmutable
    {
        $node = $this->crawler->filterXPath('//div[contains(@class, \'di-tc pt4 pb4 pl8 pr8 ar fn-grey2\')]');

        preg_match('~Aired: (.*?)$~', $node->text(), $match);
        if (empty($match)) {
            return null;
        }

        return Parser::parseDate($match[1]);
    }

    /**
     * @return bool
     */
    public function getFiller(): bool
    {
        $filler = $this->crawler->filterXPath('//span[contains(@class, \'icon-episode-type-bg\')]');

        if (!$filler->count()) {
            return false;
        }

        return trim($filler->text()) === 'Filler';
    }

    /**
     * @return bool
     */
    public function getRecap(): bool
    {
        $recap = $this->crawler->filterXPath('//span[contains(@class, \'icon-episode-type-bg\')]');

        if (!$recap->count()) {
            return false;
        }

        return trim($recap->text()) === 'Recap';
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getForumUrl(): ?string
    {
        $forum = $this->crawler->filterXPath('//td[contains(@class, \'episode-forum\')]/a');

        if (!$forum->count()) {
            return null;
        }

        return $forum->attr('href');
    }

    public function getSynopsis(): ?string
    {
        $synopsis = $this->crawler->filterXPath('//meta[@property=\'og:description\']')->attr('content');

        if (preg_match('~^Looking for episode specific information~', $synopsis)) {
            return null;
        }

        return $synopsis;
    }

    public function getDuration(): ?int
    {
        $node = $this->crawler->filterXPath('//div[contains(@class, \'di-tc pt4 pb4 pl8 pr8 ar fn-grey2\')]');

        preg_match('~Duration: (.*?)Aired~', $node->text(), $match);
        if (empty($match)) {
            return null;
        }

        return Parser::parseDurationToSeconds($match[1]);
    }
}
