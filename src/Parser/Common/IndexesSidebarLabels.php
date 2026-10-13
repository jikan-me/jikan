<?php

namespace Jikan\Parser\Common;

use Symfony\Component\DomCrawler\Crawler;

/**
 * Looks up the "Label:" spans of a detail page's sidebar without scanning the whole page once per label.
 *
 * `$crawler->filterXPath('//span[text()="Genres:"]')` walks every element of the page, and the anime and
 * manga parsers did that about 25 times. This trait evaluates one XPath for all labels of the using class
 * and buckets the matching spans, so label($name) returns exactly what the single-label query returned
 * (same nodes, same document order).
 *
 * The using class must have a `$crawler` property holding the page and a `SIDEBAR_LABELS` constant listing
 * every label it asks for, e.g. `private const SIDEBAR_LABELS = ['Genres:', 'Studios:'];`.
 */
trait IndexesSidebarLabels
{
    /** @var array<string, list<\DOMNode>>|null */
    private ?array $sidebarLabelNodes = null;

    private function label(string $label): Crawler
    {
        $this->sidebarLabelNodes ??= $this->indexSidebarLabels();

        return new Crawler($this->sidebarLabelNodes[$label] ?? []);
    }

    /**
     * @return array<string, list<\DOMNode>>
     */
    private function indexSidebarLabels(): array
    {
        $wanted = array_flip(self::SIDEBAR_LABELS);
        $conditions = array_map(static fn(string $l): string => sprintf('text()="%s"', $l), self::SIDEBAR_LABELS);

        $index = [];
        foreach ($this->crawler->filterXPath('//span[' . implode(' or ', $conditions) . ']') as $span) {
            $seen = [];
            foreach ($span->childNodes as $child) {
                if ($child->nodeType !== XML_TEXT_NODE || !isset($wanted[$child->nodeValue]) || isset($seen[$child->nodeValue])) {
                    continue;
                }
                $seen[$child->nodeValue] = true;
                $index[$child->nodeValue][] = $span;
            }
        }

        return $index;
    }
}
