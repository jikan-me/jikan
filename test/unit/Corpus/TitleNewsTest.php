<?php

namespace JikanTest\Corpus;

use Jikan\Model\News\ResourceNewsListItem;
use Jikan\Parser\News\ResourceNewsListParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\DomCrawler\Crawler;

/**
 * jikan#661: getNewsList() for an anime or manga returned no articles. A title's own news page
 * (/anime/{id}/_/news) is not laid out like the main news index (no div.news-unit): each article is a block with a
 * picSurround image, a title in p.spaceit, an excerpt, and an info line ("Oct 10, 2023 6:34 AM by Aiimee |
 * Discuss (1 comment)"), and its links are relative. The pages here are recorded live: anime 1 (9 articles),
 * anime 21 pages 1 and 2 (30 each) and manga 2 (25).
 */
class TitleNewsTest extends TestCase
{
    /**
     * @return iterable<string, array{string, int}>
     */
    public static function pages(): iterable
    {
        yield 'anime 1' => ['anime-news-1', 9];
        yield 'anime 21, page 1' => ['anime-news-21', 30];
        yield 'anime 21, page 2' => ['anime-news-21-p2', 30];
        yield 'manga 2' => ['manga-news-2', 25];
    }

    #[Test]
    #[DataProvider('pages')]
    public function every_article_on_the_page_is_returned_whole(string $id, int $count): void
    {
        $articles = new ResourceNewsListParser(Variants::crawler($id))->getResults();

        self::assertCount($count, $articles);
        foreach ($articles as $i => $a) {
            /** @var ResourceNewsListItem $a */
            $where = "$id article $i";
            self::assertGreaterThan(0, $a->getMalId(), $where);
            self::assertSame('https://myanimelist.net/news/' . $a->getMalId(), $a->getUrl(), $where);
            self::assertNotSame('', $a->getTitle(), $where);
            self::assertNotNull($a->getDate(), $where);
            self::assertSame(0, $a->getDate()->getOffset(), "$where: dates are UTC");
            self::assertSame('https://myanimelist.net/profile/' . $a->getAuthorUsername(), $a->getAuthorUrl(), $where);
            self::assertMatchesRegularExpression('~^https://myanimelist\.net/forum/\?topicid=\d+$~', $a->getForumUrl(), $where);
            self::assertGreaterThanOrEqual(0, $a->getComments(), $where);
            self::assertNotSame('', $a->getExcerpt(), $where);
            self::assertStringEndsNotWith('read more', $a->getExcerpt(), "$where: the link text is not part of the excerpt");
            $image = $a->getImages()->getJpg()->getImageUrl();
            self::assertTrue($image === null || str_starts_with($image, 'https://cdn.myanimelist.net/'), "$where: $image");
            self::assertStringNotContainsString('spacer.gif', (string) $image, $where);
        }
    }

    #[Test]
    public function the_first_article_of_anime_1_is_read_exactly(): void
    {
        $a = new ResourceNewsListParser(Variants::crawler('anime-news-1'))->getResults()[0];

        self::assertSame(69976885, $a->getMalId());
        self::assertSame('North American Anime & Manga Releases for October', $a->getTitle());
        self::assertSame('2023-10-10T06:34:00+00:00', $a->getDate()->format('c'));
        self::assertSame('Aiimee', $a->getAuthorUsername());
        self::assertSame('https://myanimelist.net/forum/?topicid=2122591', $a->getForumUrl());
        self::assertSame(1, $a->getComments());
        self::assertStringStartsWith('Here are the North American anime, manga, and light novel releases for October.', $a->getExcerpt());
        self::assertSame(
            'https://cdn.myanimelist.net/s/common/uploaded_files/1696941276-e051d08e05dbd146c5da78350b374d01.jpeg?s=dd26a6afec8aa4dafe051ce750b0fd40',
            $a->getImages()->getJpg()->getImageUrl()
        );
    }

    /**
     * @return iterable<string, array{string, bool, int}>
     */
    public static function paging(): iterable
    {
        yield 'a single page of news' => ['anime-news-1', false, 1];
        yield 'manga with one page' => ['manga-news-2', false, 1];
        yield 'first page of many' => ['anime-news-21', true, 2];
        yield 'second page of many' => ['anime-news-21-p2', true, 3];
    }

    #[Test]
    #[DataProvider('paging')]
    public function the_more_news_link_decides_the_next_page(string $id, bool $hasNext, int $last): void
    {
        $list = new ResourceNewsListParser(Variants::crawler($id))->getModel();

        self::assertSame($hasNext, $list->hasNextPage(), $id);
        self::assertSame($last, $list->getLastVisiblePage(), $id);
    }

    #[Test]
    public function the_news_tab_that_links_to_the_current_page_is_not_a_next_page(): void
    {
        // On the last page the "News" tab still links to ?p=<this page>; only "More News" goes further.
        $page = Variants::crawler('anime-news-21-p2');
        foreach ($page->filterXPath('//*[@id="content"]//a[contains(@href, "?p=3")]') as $more) {
            $more->parentNode->removeChild($more);
        }
        $list = new ResourceNewsListParser($page)->getModel();

        self::assertFalse($list->hasNextPage());
        self::assertSame(2, $list->getLastVisiblePage());
    }

    /**
     * @return iterable<string, array{string, int}>
     */
    public static function commentLines(): iterable
    {
        yield 'one comment' => ['Discuss (1 comment)', 1];
        yield 'several comments' => ['Discuss (12 comments)', 12];
        yield 'capitalised' => ['Discuss (3 Comments)', 3];
        yield 'no count shown' => ['Discuss', 0];
        yield 'none yet' => ['Discuss (0 comments)', 0];
    }

    #[Test]
    #[DataProvider('commentLines')]
    public function the_comment_count_is_read_from_the_discussion_link(string $text, int $expected): void
    {
        $page = Variants::crawler('anime-news-1');
        $link = $page->filterXPath('(//p[contains(@class, "lightLink")])[1]/a[last()]')->getNode(0);
        $link->textContent = $text;

        self::assertSame($expected, new ResourceNewsListParser($page)->getResults()[0]->getComments());
    }

    /**
     * @return iterable<string, array{string, string, bool, int}>
     */
    public static function urls(): iterable
    {
        // a page of news with no further page, requested with and without a page number
        yield 'no page number, last page' => ['anime-news-1', 'https://myanimelist.net/anime/1/_/news', false, 1];
        yield 'page 0 is page 1' => ['anime-news-1', 'https://myanimelist.net/anime/1/_/news?p=0', false, 1];
        yield 'page 1, last page' => ['anime-news-1', 'https://myanimelist.net/anime/1/_/news?p=1', false, 1];
        // a page that links "More News" to page 2: asked for without a page number it is page 1, so page 2 is next
        yield 'no page number, more to come' => ['anime-news-21', 'https://myanimelist.net/anime/21/_/news', true, 2];
    }

    #[Test]
    #[DataProvider('urls')]
    public function a_url_without_a_usable_page_number_is_the_first_page(string $id, string $uri, bool $hasNext, int $last): void
    {
        $page = new Crawler(null, $uri);
        $page->addHtmlContent(Variants::page($id)[1]);
        $list = new ResourceNewsListParser($page)->getModel();

        self::assertSame($hasNext, $list->hasNextPage());
        self::assertSame($last, $list->getLastVisiblePage());
    }

    #[Test]
    public function a_title_with_no_news_has_no_articles(): void
    {
        $page = new Crawler('<html><body><div id="content"><p>No news has been added yet.</p></div></body></html>', 'https://myanimelist.net/anime/9/_/news?p=1');
        $list = new ResourceNewsListParser($page)->getModel();

        self::assertSame([], $list->getResults());
        self::assertFalse($list->hasNextPage());
    }

    #[Test]
    public function the_main_news_index_still_reads_its_own_layout(): void
    {
        // The index has div.news-unit and no picSurround blocks: the original parser is used for it.
        $articles = new ResourceNewsListParser(Variants::crawler('recent-news-p1'))->getResults();

        self::assertCount(20, $articles);
        self::assertContainsOnlyInstancesOf(ResourceNewsListItem::class, $articles);
        self::assertNotSame('', $articles[0]->getTitle());
    }
}
