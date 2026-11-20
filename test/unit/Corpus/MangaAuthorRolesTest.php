<?php

namespace JikanTest\Corpus;

use Jikan\Parser\Manga\MangaParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * MyAnimeList prints each manga author's role beside their link — "Urasawa, Naoki (Story &amp; Art)",
 * and per author on a multi-author title. jikan#569 asks for it; getAuthors() keeps the bare
 * MalUrl[] it always returned, and getAuthorsWithRoles() carries the role.
 */
class MangaAuthorRolesTest extends TestCase
{
    #[Test]
    public function a_single_author_carries_their_role(): void
    {
        $monster = (new MangaParser(Variants::crawler('manga-1')))->getModel();

        $authors = $monster->getAuthorsWithRoles();
        self::assertCount(1, $authors);

        self::assertSame('Urasawa, Naoki', $authors[0]->getName());
        self::assertSame('Story & Art', $authors[0]->getRole());
        self::assertSame(1867, $authors[0]->getMalId());
        self::assertSame('https://myanimelist.net/people/1867/Naoki_Urasawa', $authors[0]->getUrl());
    }

    #[Test]
    public function each_author_of_a_multi_author_title_carries_their_own_role(): void
    {
        // "Hasekura, Isuna (Story), Ayakura, Juu (Art)"
        $spice = (new MangaParser(Variants::crawler('manga-9115')))->getModel();

        $byName = [];
        foreach ($spice->getAuthorsWithRoles() as $author) {
            $byName[$author->getName()] = $author->getRole();
        }

        self::assertSame(
            ['Hasekura, Isuna' => 'Story', 'Ayakura, Juu' => 'Art'],
            $byName
        );
    }

    #[Test]
    public function the_bare_authors_getter_is_unchanged(): void
    {
        $monster = (new MangaParser(Variants::crawler('manga-1')))->getModel();

        self::assertCount(1, $monster->getAuthors());
        self::assertSame('Urasawa, Naoki', $monster->getAuthors()[0]->getName());
    }
}
