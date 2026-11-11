<?php

namespace JikanTest\Corpus;

use Jikan\Model\Search\AnimeSearchListItem;
use Jikan\Parser\Search\AnimeSearchParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * A search result whose title has no synopsis still carries `<div class="pt4"></div>` on the page —
 * an empty block, which used to parse as `''`. MyAnimeList not showing a value means `null` in this
 * library (DECISIONS.md, "Missing and unreadable data"), so the getter is `?string`.
 */
class SearchSynopsisTest extends TestCase
{
    #[Test]
    public function a_result_with_an_empty_synopsis_block_is_null(): void
    {
        $search = (new AnimeSearchParser(Variants::crawler('anime-search-fate')))->getModel();

        $withoutSynopsis = array_values(
            array_filter($search->getResults(), static fn (AnimeSearchListItem $item): bool => $item->getSynopsis() === null)
        );

        self::assertCount(1, $withoutSynopsis);
        self::assertSame('Unmei', $withoutSynopsis[0]->getTitle());

        foreach ($search->getResults() as $item) {
            if ($item->getSynopsis() === null) {
                continue;
            }

            self::assertNotSame('', $item->getSynopsis(), $item->getTitle() . ' kept an empty synopsis.');
        }
    }
}
