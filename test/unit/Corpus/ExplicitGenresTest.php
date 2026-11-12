<?php

namespace JikanTest\Corpus;

use Jikan\Parser\Anime\AnimeParser;
use Jikan\Parser\Manga\MangaParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * `getExplicitGenres()` is deprecated because MyAnimeList removed the separate "Explicit Genres" row:
 * the getter can only return `[]`, and explicit genres now appear among the genres. These assertions
 * make that premise executable, so the deprecation and its `DECISIONS.md` entry have a witness in the
 * suite rather than only a sentence.
 */
class ExplicitGenresTest extends TestCase
{
    #[Test]
    public function explicit_genres_are_listed_among_the_genres(): void
    {
        // anime/11617 is rated R+ and lists Ecchi — exactly the title the old row used to separate.
        $anime = (new AnimeParser(Variants::crawler('anime-11617')))->getModel();

        self::assertSame([], $anime->getExplicitGenres());

        $genres = array_map(static fn ($genre): string => $genre->getName(), $anime->getGenres());
        self::assertContains('Ecchi', $genres);
    }

    #[Test]
    public function the_manga_getter_is_empty_too(): void
    {
        $manga = (new MangaParser(Variants::crawler('manga-1')))->getModel();

        self::assertSame([], $manga->getExplicitGenres());
    }
}
