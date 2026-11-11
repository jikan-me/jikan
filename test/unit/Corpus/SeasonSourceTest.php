<?php

namespace JikanTest\Corpus;

use Jikan\Parser\Seasonal\SeasonalParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * MyAnimeList renders a card's "Source" property even for a title it has no source for — the caption
 * is there and the item span is empty. That used to parse as `''`; absence is `null` in this library
 * (DECISIONS.md, "Missing and unreadable data").
 */
class SeasonSourceTest extends TestCase
{
    #[Test]
    public function a_card_without_a_source_is_null(): void
    {
        $seasonal = (new SeasonalParser(Variants::crawler('seasonal-1990-spring')))->getModel();

        $sources = [];
        foreach ($seasonal->getAnime() as $card) {
            $sources[$card->getTitle()] = $card->getSource();
        }

        self::assertArrayHasKey('Eigo de Asobo: Tanken Goblin Tou', $sources);
        self::assertNull($sources['Eigo de Asobo: Tanken Goblin Tou'], 'An empty Source item is absence, not an empty string.');
        self::assertSame('Original', $sources['Fushigi no Umi no Nadia'], 'A card that does list a source keeps its value.');

        foreach ($sources as $title => $source) {
            self::assertNotSame('', $source, $title . ' kept an empty source.');
        }
    }
}
