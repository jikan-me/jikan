<?php

namespace Jikan\Parser\Manga;

use Jikan\Model\Manga\Manga;
use Jikan\Model\Manga\MangaUserUpdates;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class MangaRecentlyUpdatedByUsersParser
 *
 * @package Jikan\Parser\Manag
 */
class MangaRecentlyUpdatedByUsersParser
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * MangaRecentlyUpdatedByUsersParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return array
     * @throws \InvalidArgumentException
     */
    public function getResults(): array
    {
        try {
            return $this->crawler
                ->filterXPath('//table[@class="table-recently-updated"]/tbody/tr[1]')
                ->nextAll()
                ->each(
                    function ($c) {
                        return new MangaRecentlyUpdatedByUsersListParser($c)->getModel();
                    }
                );
        } catch (\Exception $e) {
            return [];
        }
    }


    // @todo anime user updates pagination
    public function getHasNextPage(): bool
    {
        return $this->nextPage() !== null;
    }

    /**
     * The page only links the next one ("Next Page", ?show=N with 75 members per page), not the last, so this is
     * the furthest page it lets you reach: the next page when there is one.
     */
    public function getLastPage(): int
    {
        return $this->nextPage() ?? 1;
    }

    private function nextPage(): ?int
    {
        foreach ($this->crawler->filterXPath('//div[contains(@class, "spaceit")]/a[contains(@href, "show=")]') as $link) {
            if ($link instanceof \DOMElement && str_contains($link->textContent, 'Next') && preg_match('~[?&]show=(\d+)~', $link->getAttribute('href'), $m)) {
                return intdiv((int) $m[1], 75) + 1;
            }
        }

        return null;
    }

    public function getModel(): MangaUserUpdates
    {
        return MangaUserUpdates::fromParser($this);
    }
}
