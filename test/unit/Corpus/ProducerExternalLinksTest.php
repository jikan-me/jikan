<?php

namespace JikanTest\Corpus;

use Jikan\Model\Common\Url;
use Jikan\Parser\Producer\ProducerParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

/**
 * A producer page lists its links in two sections, "Available At" and "Resources". When one section was
 * missing, getExternalLinks() returned no link at all, dropping the other section too (e.g. a studio with an
 * official site but no "Resources" link). The recorded Pierrot page has both: removing one section must keep
 * the other's links.
 */
class ProducerExternalLinksTest extends TestCase
{
    /**
     * @return iterable<string, array{string, int, string}>
     */
    public static function sections(): iterable
    {
        yield 'without "Resources"' => ['pb16', 5, 'http://pierrot.jp/'];
        yield 'without "Available At"' => ['user-profile-sns', 6, 'https://pierrot.jp/company/'];
    }

    #[Test]
    #[DataProvider('sections')]
    public function the_other_section_keeps_its_links(string $removedClass, int $count, string $firstUrl): void
    {
        $page = Variants::crawler('producer-1-p1');
        $section = $page->filterXPath('//*[@id="content"]/div[1]/div[contains(@class, "' . $removedClass . '")]')->getNode(0);
        $section->parentNode->removeChild($section);

        $links = new ProducerParser($page)->getExternalLinks();

        self::assertCount($count, $links);
        self::assertContainsOnlyInstancesOf(Url::class, $links);
        self::assertSame($firstUrl, $links[0]->getUrl());
    }
}
