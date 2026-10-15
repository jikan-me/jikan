<?php

namespace Jikan\Parser\News;

use Jikan\Helper\Constants;
use Jikan\Helper\JString;
use Jikan\Helper\Parser;

/**
 * One article on a title's own news page (/anime/{id}/_/news, /manga/{id}/_/news).
 *
 * That page has a different layout from the main news index: no div.news-unit, but a block per article with a
 * picSurround image, the title in a p.spaceit, an excerpt, and an info line ("Oct 10, 2023 6:34 AM by Aiimee |
 * Discuss (1 comment)"). It builds the same ResourceNewsListItem; only where each value is read from differs, and
 * the links are relative.
 *
 * @package Jikan\Parser
 */
class TitleNewsListItemParser extends ResourceNewsListItemParser
{
    private const TITLE = './/p[contains(@class, "spaceit") and not(contains(@class, "lightLink"))]/a';
    private const INFO = './/p[contains(@class, "lightLink")]';

    /**
     * @throws \InvalidArgumentException
     */
    public function getTitle(): string
    {
        return JString::cleanse($this->crawler->filterXPath(self::TITLE)->text());
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function getUrl(): string
    {
        return self::absolute($this->crawler->filterXPath(self::TITLE)->attr('href'));
    }

    /**
     * The page loads images lazily: src is a spacer, the picture is in data-src.
     *
     * @throws \InvalidArgumentException
     */
    public function getImage(): ?string
    {
        $image = $this->crawler->filterXPath('.//*[contains(@class, "image-link")]/img');
        $source = $image->count() ? $image->attr('data-src') : null;

        return $source === null ? null : Parser::parseImageQuality($source);
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function getDate(): ?\DateTimeImmutable
    {
        return Parser::parseDate(
            explode(' by', JString::cleanse($this->crawler->filterXPath(self::INFO)->text()))[0]
        );
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function getDiscussionLink(): string
    {
        return self::absolute($this->crawler->filterXPath(self::INFO . '/a[last()]')->attr('href'));
    }

    /**
     * "Discuss (1 comment)", "Discuss (12 comments)".
     *
     * @throws \InvalidArgumentException
     */
    public function getComments(): int
    {
        preg_match('~\((\d+) comments?\)~i', $this->crawler->filterXPath(self::INFO . '/a[last()]')->text(), $matches);

        return (int) ($matches[1] ?? 0);
    }

    /**
     * The excerpt, without the "read more" link that ends it.
     *
     * @throws \InvalidArgumentException
     */
    public function getIntro(): string
    {
        $text = JString::cleanse($this->crawler->filterXPath('.//div[contains(@style, "overflow")]/p')->text());

        return rtrim(preg_replace('~read more$~i', '', $text));
    }

    private static function absolute(string $href): string
    {
        return str_starts_with($href, '/') ? Constants::BASE_URL . $href : $href;
    }
}
