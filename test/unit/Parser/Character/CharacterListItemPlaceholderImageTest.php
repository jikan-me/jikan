<?php

namespace JikanTest\Parser\Character;

use Jikan\Parser\Character\CharacterListItemParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class CharacterListItemPlaceholderImageTest
 *
 * Parser-path coverage for jikan-me/jikan#488: a character list row whose
 * thumb is a MyAnimeList placeholder image must parse to `null`, while real
 * artwork URLs keep flowing through. Uses a synthetic Crawler directly so the
 * test stays hermetic (no HTTP, no recorded fixture required).
 */
class CharacterListItemPlaceholderImageTest extends TestCase
{
    private function parserWithImage(string $dataSrc): CharacterListItemParser
    {
        $html = <<<HTML
<html>
<body>
<table>
  <tbody>
    <tr>
      <td><div class="picSurround"><a href="https://myanimelist.net/character/116281/Momonga"><img class="lazyload" data-src="{$dataSrc}"></a></div></td>
      <td>
        <div></div>
        <div><h3 class="h3_character_name"><a href="https://myanimelist.net/character/116281/Momonga">Momonga</a></h3></div>
        <div><a href="https://myanimelist.net/character/116281/Momonga">Momonga</a></div>
        <div>Main</div>
        <div>13947</div>
      </td>
    </tr>
  </tbody>
</table>
</body>
</html>
HTML;

        return new CharacterListItemParser(new Crawler($html));
    }

    #[Test]
    public function it_parses_a_placeholder_thumb_to_null(): void
    {
        $parser = $this->parserWithImage(
            'https://cdn.myanimelist.net/r/42x62/images/questionmark_23.gif?s=f7dcbc4a4603d18356d3dfef8abd655c'
        );

        self::assertNull($parser->getImage());
        self::assertNull($parser->getCharacterMeta()->getImages()->getJpg()->getImageUrl());
        self::assertNull($parser->getCharacterMeta()->getImages()->getWebp()->getImageUrl());
    }

    #[Test]
    public function it_keeps_real_artwork_untouched(): void
    {
        $parser = $this->parserWithImage(
            'https://cdn.myanimelist.net/r/42x62/images/characters/15/284859.jpg?s=baed7a851bb385725b6ad6bdbfb98a6c'
        );

        self::assertSame(
            'https://cdn.myanimelist.net/images/characters/15/284859.jpg?s=baed7a851bb385725b6ad6bdbfb98a6c',
            $parser->getImage()
        );
        self::assertNotNull($parser->getCharacterMeta()->getImages()->getJpg()->getImageUrl());
        self::assertNotNull($parser->getCharacterMeta()->getImages()->getWebp()->getImageUrl());
    }

    #[Test]
    public function it_still_parses_the_rest_of_the_row(): void
    {
        $parser = $this->parserWithImage(
            'https://cdn.myanimelist.net/images/questionmark_23.gif'
        );

        self::assertSame(116281, $parser->getMalId());
        self::assertSame('Momonga', $parser->getName());
        self::assertSame('Main', $parser->getRole());
        self::assertSame(13947, $parser->getFavorites());
    }
}