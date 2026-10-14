<?php

namespace JikanTest\Parser\Common;

use Jikan\Parser\Common\IndexesSidebarLabels;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;

/**
 * label() must return exactly the nodes `filterXPath('//span[text()="Label:"]')` returned.
 */
class IndexesSidebarLabelsTest extends TestCase
{
    private const HTML = <<<'HTML'
        <html><body>
          <div class="sidebar">
            <div><span class="dark_text">Genres:</span> <a>Action</a></div>
            <div><span class="dark_text">Studios:</span> <a>Madhouse</a></div>
            <div><span>Genres:</span> <a>Duplicate label further down</a></div>
            <div><span><b>x</b>Studios:<i>y</i></span></div>
            <div><span>Studios: </span> not an exact match</div>
            <div><span>Other:</span></div>
          </div>
        </body></html>
        HTML;

    private function parser(Crawler $page): object
    {
        return new class ($page) {
            use IndexesSidebarLabels;

            private const SIDEBAR_LABELS = ['Genres:', 'Studios:', 'Missing:'];

            public function __construct(private Crawler $crawler)
            {
            }

            public function find(string $label): Crawler
            {
                return $this->label($label);
            }
        };
    }

    /**
     * @return list<string> outer HTML of each node
     */
    private function html(Crawler $nodes): array
    {
        return array_map(static fn (\DOMNode $n): string => $n->ownerDocument->saveHTML($n), iterator_to_array($nodes, false));
    }

    #[Test]
    #[\PHPUnit\Framework\Attributes\DataProvider('labels')]
    public function it_returns_the_same_nodes_as_the_single_label_xpath(string $label): void
    {
        $page = new Crawler();
        $page->addHtmlContent(self::HTML);

        $expected = $page->filterXPath(sprintf('//span[text()="%s"]', $label));

        self::assertSame($this->html($expected), $this->html($this->parser($page)->find($label)));
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function labels(): iterable
    {
        yield 'label that appears twice, in document order' => ['Genres:'];
        yield 'label split across text nodes by child elements' => ['Studios:'];
        yield 'label that is absent from the page' => ['Missing:'];
    }

    #[Test]
    public function it_indexes_once_and_answers_unknown_labels_with_an_empty_crawler(): void
    {
        $page = new Crawler();
        $page->addHtmlContent(self::HTML);
        $parser = $this->parser($page);

        self::assertCount(2, $parser->find('Genres:'));
        self::assertCount(2, $parser->find('Genres:'));
        self::assertCount(0, $parser->find('Not requested:'));
    }
}
