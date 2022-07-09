<?php

namespace Jikan\Parser\Anime;

use Jikan\Helper\JString;
use Jikan\Helper\Parser;
use Jikan\Model\Anime\EpisodeListItem;
use Jikan\Model\Common\DateRange;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class EpisodeListItemParser
 *
 * @package Jikan\Parser\Episode
 */
class EpisodeListItemParser implements ParserInterface
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
     * @return EpisodeListItem
     * @throws \InvalidArgumentException
     */
    public function getModel(): EpisodeListItem
    {
        return EpisodeListItem::fromParser($this);
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getEpisodeId(): int
    {
        return (int)$this->crawler->filterXPath('//td[contains(@class, \'episode-number\')]')->text();
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getEpisodeUrl(): string
    {
        return $this->crawler->filterXPath('//td[contains(@class,"episode-title")]/a')->attr('href');
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getTitle(): string
    {
        return $this->crawler->filterXPath('//td[contains(@class, "episode-title")]/a')->text();
    }

    /**
     * @return null|string
     * @throws \InvalidArgumentException
     */
    public function getTitleJapanese(): ?string
    {
        $title = $this->crawler->filterXPath('//td[contains(@class, "episode-title")]/span[@class=\'di-ib\']')->text();

        if (empty($title)) {
            return null;
        }

        preg_match('~(.*)\((.*)\)~', $title, $matches);

        return (!empty($matches[2]) ? $matches[2] : null);
    }

    /**
     * @return null|string
     * @throws \InvalidArgumentException
     */
    public function getTitleRomanji(): ?string
    {
        $title = $this->crawler->filterXPath('//td[contains(@class, "episode-title")]/span[@class=\'di-ib\']')->text();

        if (empty($title)) {
            return null;
        }

        preg_match('~(.*)\((.*)\)~', $title, $matches);

        return (!empty($matches[1]) ? $matches[1] : null);
    }


    /**
     * @return \DateTimeImmutable|null
     * @throws \Exception
     */
    public function getAired(): ?\DateTimeImmutable
    {
        $aired = $this->crawler->filterXPath('//td[contains(@class, \'episode-aired\')]')->text();

        if ($aired === 'N/A') {
            return null;
        }

        return Parser::parseDateMDYReadable($aired);
    }


    /**
     * @return float|null
     */
    public function getScore(): ?float
    {
        $node = $this->crawler
            ->filterXPath('//td[contains(@class, \'episode-poll\')]/div[contains(@class, "average")]/span');

        if (!$node->count()) {
            return null;
        }

        $score = $node->text();

        if (!JString::isStringFloat($score)) {
            return null;
        }

        return (float) $score;
    }

    /**
     * @return bool
     */
    public function getFiller(): bool
    {
        $filler = $this->crawler->filterXPath(
            '//td[contains(@class,"episode-title")]
                /span[contains(@class, \'icon-episode-type-bg\') and contains(text(), \'Filler\')]'
        );

        if (!$filler->count()) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function getRecap(): bool
    {
        $recap = $this->crawler->filterXPath(
            '//td[contains(@class,"episode-title")]
                /span[contains(@class, \'icon-episode-type-bg\') and contains(text(), \'Recap\')
            ]'
        );

        if (!$recap->count()) {
            return false;
        }

        return true;
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getVideoUrl(): ?string
    {
        $video = $this->crawler->filterXPath('//td[contains(@class, \'episode-video\')]/a');

        if (!$video->count()) {
            return null;
        }

        return $video->attr('href');
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
}
