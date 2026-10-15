<?php

namespace JikanTest\Corpus;

use Jikan\Parser\User\Reviews\UserReviewsParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\DomCrawler\Crawler;

/**
 * A user's reviews list 10 per page and link only the next page ("More Reviews", ?p=N), never the last.
 * hasNextPage() used to be hard-coded to true and getLastVisiblePage() to 1, so paging never ended.
 * The recorded profile has 5 reviews (one page); the other cases add the link as MyAnimeList renders it.
 */
class UserReviewsPaginationTest extends TestCase
{
    #[Test]
    public function a_single_page_of_reviews_has_no_next_page(): void
    {
        $parser = new UserReviewsParser(self::page('https://myanimelist.net/profile/DatRandomDude/reviews?p=1'));

        self::assertFalse($parser->hasNextPage());
        self::assertSame(1, $parser->getLastVisiblePage());
    }

    #[Test]
    public function a_more_reviews_link_means_there_is_a_next_page(): void
    {
        $page = self::page('https://myanimelist.net/profile/DatRandomDude/reviews?p=1', ['2']);
        $parser = new UserReviewsParser($page);

        self::assertTrue($parser->hasNextPage());
        self::assertSame(2, $parser->getLastVisiblePage());
    }

    #[Test]
    public function a_link_back_to_an_earlier_page_is_not_a_next_page(): void
    {
        $parser = new UserReviewsParser(self::page('https://myanimelist.net/profile/DatRandomDude/reviews?p=3', ['2']));

        self::assertFalse($parser->hasNextPage());
        self::assertSame(3, $parser->getLastVisiblePage());
    }

    /**
     * The recorded reviews page, served as $uri, with a "More Reviews" link for each of $links.
     *
     * @param list<string> $links page numbers
     */
    private static function page(string $uri, array $links = []): Crawler
    {
        [, $html] = Variants::page('user-reviews-datrandomdude');
        $crawler = new Crawler(null, $uri);
        $crawler->addHtmlContent($html);
        $content = $crawler->filterXPath('//*[@id="content"]')->getNode(0);
        foreach ($links as $p) {
            $div = $content->ownerDocument->createElement('div');
            $div->setAttribute('class', 'mt4 mb8');
            $a = $content->ownerDocument->createElement('a', 'More Reviews');
            $a->setAttribute('href', "https://myanimelist.net/profile/DatRandomDude/reviews?p=$p");
            $a->setAttribute('class', 'ga-click');
            $div->appendChild($a);
            $content->appendChild($div);
        }

        return $crawler;
    }
}
