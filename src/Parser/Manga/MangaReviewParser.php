<?php

namespace Jikan\Parser\Manga;

use Jikan\Helper\JString;
use Jikan\Helper\Parser;
use Jikan\Model\Manga\MangaReview;
use Jikan\Model\Manga\MangaReviewer;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class MangaReviewParser
 *
 * @package Jikan\Parser
 */
class MangaReviewParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * MangaReviewParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return MangaReview
     * @throws \Exception
     * @throws \RuntimeException
     */
    public function getModel(): MangaReview
    {
        return MangaReview::fromParser($this);
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getId(): int
    {
        parse_str(parse_url($this->getUrl(), PHP_URL_QUERY), $params);
        return (int) $params['id'];
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getUrl(): string
    {
        $node = $this->crawler->filterXPath('//div[1]/div[3]/div/div/a');
        return $node->attr('href');
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getHelpfulCount(): int
    {
        $node = $this->crawler->filterXPath('//div[1]/div[1]/div[2]/table/tr/td[2]/div/strong/span');
        return (int) $node->text();
    }

    /**
     * @return \DateTimeImmutable
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    public function getDate(): \DateTimeImmutable
    {
        $date = $this->crawler->filterXPath('//div[1]/div[1]/div[1]/div[1]')->text();
        $time = $this->crawler->filterXPath('//div[1]/div[1]/div[1]/div[1]')->attr('title');
        return new \DateTimeImmutable("{$date} {$time}", new \DateTimeZone('UTC'));
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getContent(): string
    {
//        echo htmlentities(
//            $this->crawler
//                ->filterXPath('//div[contains(@class, "textReadability")]')
//                ->html()
//        );
//        echo "<br><br>";
//
//        return $this->crawler
//            ->filterXPath('//div[2]')
//            ->text();
        $node = $this->crawler->filterXPath('//div[1]/div[2]');
        $nodeExpanded = $node->filterXPath('//span');

        $node = Parser::removeChildNodes($node);

        $content = JString::cleanse($node->text());

        if ($nodeExpanded->count()) {
            $expandedContent = JString::cleanse(Parser::removeChildNodes($nodeExpanded)->html());
            $content .= $expandedContent;
        }

        return $content;
    }

    /**
     * @return MangaReviewer
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    public function getReviewer(): MangaReviewer
    {
        return (new MangaReviewerParser($this->crawler))->getModel();
    }
}
