<?php

namespace JikanTest\Parser\News;

use Jikan\Http\HttpClientWrapper;
use Jikan\MyAnimeList\MalClient;
use Jikan\Request\News\NewsByTagRequest;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class NewsByTagTest
 */
class NewsByTagTest extends TestCase
{
    /**
     * @var RecentNewsParserTest
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $request = new \Jikan\Request\News\NewsByTagRequest('fall_2024');
        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', $request->getPath());
        $this->parser = new \Jikan\Parser\News\NewsListParser($crawler);
    }

    #[Test]
    public function it_gets_results()
    {
        self::assertEquals(20, count($this->parser->getResults()));
    }

    #[Test]
    public function it_gets_result_item()
    {
        $entry = $this->parser->getResults()[0];

        self::assertEquals(72151278, $entry->getMalId());
        self::assertEquals("https://myanimelist.net/news/72151278", $entry->getUrl());
        self::assertInstanceOf(\DateTimeImmutable::class, $entry->getDate());
        self::assertEquals("Vindstot", $entry->getAuthorUsername());
        self::assertEquals("https://myanimelist.net/profile/Vindstot", $entry->getAuthorUrl());
        self::assertEquals("https://cdn.myanimelist.net/s/common/uploaded_files/1734755485-2e3720eda48f241b354707a867bcb0bd.jpeg?s=338a34101aaab494b01e445a779068d4", $entry->getImages()->getJpg()->getImageUrl());
        self::assertEquals(0, $entry->getComments());
        self::assertStringContainsString("The Rurouni Kenshin: Meiji Kenkaku Romantan - Kyoto Douran", $entry->getExcerpt());
        self::assertCount(5, $entry->getTags());
        self::assertEquals("OP ED", (string) $entry->getTags()[1]);
    }
}
