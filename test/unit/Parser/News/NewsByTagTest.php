<?php

namespace JikanTest\Parser\News;

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
    private $data;

    public function setUp(): void
    {
        parent::setUp();

        $this->data = (new MalClient())
            ->getNewsByTag(
                new NewsByTagRequest("fall_2024")
            );
    }

    #[Test]
    public function it_gets_results()
    {
        self::assertEquals(20, count($this->data->getResults()));
    }

    #[Test]
    public function it_gets_result_item()
    {
        $entry = $this->data->getResults()[0];

        self::assertEquals(71145912, $entry->getMalId());
        self::assertEquals("https://myanimelist.net/news/71145912", $entry->getUrl());
        self::assertInstanceOf(\DateTimeImmutable::class, $entry->getDate());
        self::assertEquals("Hyperion_PS", $entry->getAuthorUsername());
        self::assertEquals("https://myanimelist.net/profile/Hyperion_PS", $entry->getAuthorUrl());
        self::assertEquals("https://cdn.myanimelist.net/s/common/uploaded_files/1717090995-216f46f6c0b7786ff6c6485c56d4e9a8.jpeg?s=2304e0a33535edcd50422b4a3aae06b8", $entry->getImages()->getJpg()->getImageUrl());
        self::assertEquals(2, $entry->getComments());
        self::assertStringContainsString("Blue Lock 2nd Season television anime unveiled", $entry->getExcerpt());
        self::assertCount(2, $entry->getTags());
        self::assertEquals("Fall 2024", (string) $entry->getTags()[1]);
    }
}
