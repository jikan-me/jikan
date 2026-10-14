<?php

namespace JikanTest\Parser\User;

use JikanTest\ModelSnapshot;
use Jikan\Http\HttpClientWrapper;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class ClubParserTest
 *
 * Endpoint-parser coverage for Jikan\Parser\User\ClubParser (getUserClubs)
 * against a user's clubs page.
 */
class ClubParserTest extends TestCase
{
    private function parse(): array
    {
        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', 'https://myanimelist.net/profile/DatRandomDude/clubs');

        return (new \Jikan\Parser\User\ClubParser($crawler))->getClubs();
    }

    #[Test]
    public function it_parses_the_club_list(): void
    {
        self::assertCount(30, $this->parse());
    }

    #[Test]
    public function it_parses_the_first_clubs(): void
    {
        $clubs = $this->parse();
        self::assertSame('100k+ Club', $clubs[0]->getName());
        self::assertSame(84943, $clubs[0]->getMalId());
        self::assertSame(
            'https://myanimelist.net/clubs.php?cid=84943',
            $clubs[0]->getUrl()
        );
        self::assertSame('20+', $clubs[1]->getName());
        self::assertSame(25565, $clubs[1]->getMalId());
    }

    #[Test]
    public function it_builds_a_model_that_matches_its_snapshot(): void
    {
        ModelSnapshot::assertMatches($this, $this->parse());
    }
}
