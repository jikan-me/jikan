<?php

namespace Jikan\Parser\Person;

use Jikan\Helper\JString;
use Jikan\Helper\Parser;
use Jikan\Model;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class PersonParser
 *
 * @package Jikan\Parser
 */
class PersonParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * PersonParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * Return the model
     *
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @throws \Exception
     * @throws \Exception
     */
    public function getModel(): Model\Person\Person
    {
        return Model\Person\Person::fromParser($this);
    }


    /**
     * @return int
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getPersonId(): int
    {
        preg_match('#https?://myanimelist.net/people/(\d+)#', $this->getPersonURL(), $matches);

        return (int)$matches[1];
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getPersonURL(): string
    {
        return $this->crawler->filterXPath('//meta[@property=\'og:url\']')->attr('content');
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getPersonName(): string
    {
        return JString::cleanse($this->crawler->filterXPath('//meta[@property=\'og:title\']')->attr('content'));
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getPersonImageUrl(): string
    {
        return $this->crawler->filterXPath('//meta[@property=\'og:image\']')->attr('content');
    }

    /**
     * @return string|null
     * @throws \InvalidArgumentException
     */
    public function getPersonGivenName(): ?string
    {
        $node = $this->crawler
            ->filterXPath('//span[text()="Given name:"]');

        if (!$node->count()) {
            return null;
        }

        $givenName = JString::cleanse(
            str_replace($node->text(), '', $node->ancestors()->text())
        );

        if (empty($givenName)) {
            return null;
        }

        return $givenName;
    }

    /**
     * @return string|null
     * @throws \InvalidArgumentException
     */
    public function getPersonFamilyName(): ?string
    {
        $node = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]/span[text()="Family name:"]');

        if (!$node->count()) {
            return null;
        }

        // MAL screwed up the HTML here
        preg_match(
            '~Family name:(.*?)(Alternate names|Birthday|Website|Member Favorites|More)~',
            $node->ancestors()->text(),
            $matches
        );

        if (empty($matches)) {
            return null;
        }

        $familyName = JString::cleanse($matches[1]);

        if (empty($familyName)) { // MAL has it empty at some places
            return null;
        }

        return $familyName;
    }

    /**
     * @return array|null
     * @throws \InvalidArgumentException
     */
    public function getPersonAlternateNames(): array
    {
        $node = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]/div/span[text()="Alternate names:"]');

        if (!$node->count()) {
            return [];
        }

        $names = explode(
            ',',
            str_replace($node->text(), '', $node->ancestors()->text())
        );

        foreach ($names as &$name) {
            $name = JString::cleanse($name);
        }

        return $names;
    }

    /**
     * @return string|null
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getPersonWebsite(): ?string
    {
        $node = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]/span[text()="Website:"]');


        $website = $node->nextAll()->filter('a');

        if (!$website->count()) {
            return null;
        }

        // MAL returns an empty `<a href="http://"></a>` when there's no website
        if (empty($website->text())) {
            return null;
        }


        return $website->attr('href');
    }

    /**
     * @return \DateTimeImmutable|null
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    public function getPersonBirthday(): ?\DateTimeImmutable
    {
        $node = $this->crawler
            ->filterXPath('//span[text()="Birthday:"]');

        if (!$node->count()) {
            return null;
        }

        return Parser::parseDateMDYReadable(
            JString::cleanse(
                str_replace($node->text(), '', $node->ancestors()->text())
            )
        );
    }

    /**
     * @return int|null
     * @throws \InvalidArgumentException
     */
    public function getPersonFavorites(): ?int
    {
        $node = $this->crawler
            ->filterXPath('//span[text()="Member Favorites:"]');


        if (!$node->count()) {
            return null;
        }

        return (int)JString::cleanse(
            str_replace([$node->text(), ','], '', $node->ancestors()->text())
        );
    }

    /**
     * @return string|null
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getPersonAbout(): ?string
    {
        $node = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[@class="borderClass"]')
            ->filter('.people-informantion-more');

        if (!$node->count()) {
            return null;
        }

        if (empty($node->text())) {
            return null;
        }

        return JString::cleanse(
            $node->html()
        );
    }


    /**
     * @return \Jikan\Model\Person\VoiceActingRole[]
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getPersonVoiceActingRoles(): array
    {
        $node = $this->crawler
            ->filterXPath('//table[contains(@class, "js-table-people-character")]/tr[contains(@class, "js-people-character")]');

        if (!$node->count()) {
            return [];
        }

        return $node->each(
            function (Crawler $c) {
                return (new VoiceActingRoleParser($c))->getModel();
            }
        );
    }

    /**
     * @return \Jikan\Model\Person\AnimeStaffPosition[]
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getPersonAnimeStaffPositions(): array
    {
        $node = $this->crawler
            ->filterXPath('//table[contains(@class, "js-table-people-staff")]/tr[contains(@class, "js-people-staff")]');

        if (!$node->count()) {
            return [];
        }

        return $node->each(
            function (Crawler $c) {
                return (new AnimeStaffPositionParser($c))->getModel();
            }
        );
    }

    /**
     * @return \Jikan\Model\Person\PublishedManga[]
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getPersonPublishedManga(): array
    {
        $node = $this->crawler
            ->filterXPath('//table[contains(@class, "js-table-people-manga")]/tr[contains(@class, "js-people-manga")]');

        if (!$node->count()) {
            return [];
        }

        return $node->each(
            function (Crawler $c) {
                return (new PublishedMangaParser($c))->getModel();
            }
        );
    }
}
