<?php

namespace Jikan\Parser\Club;

use Jikan\Helper\Constants;
use Jikan\Helper\JString;
use Jikan\Helper\Parser;
use Jikan\Model\Club\Club;
use Jikan\Model\Common\MalUrl;
use Jikan\Model\Common\UserMetaBasic;
use Jikan\Parser\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class ClubParser
 *
 * @package Jikan\Parser\Club
 */
class ClubParser implements ParserInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * ClubParser constructor.
     *
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * Return the model
     */
    public function getModel(): Club
    {
        return Club::fromParser($this);
    }

    /**
     * @return int
     */
    public function getMalId(): int
    {
        parse_str(parse_url($this->getUrl(), PHP_URL_QUERY), $params);
        return (int) $params['cid'];
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->crawler->filterXPath('//meta[@property=\'og:url\']')->attr('content');
    }

    /**
     * @return string
     */
    public function getImageUrl(): string
    {
        return Parser::parseImageQuality(
            $this->crawler
                ->filterXPath('//div[@id="content"]/table/tr/td[2]/div/div[1]/img')
                ->attr('data-src')
        );
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->crawler->filterXPath('//div[@id="contentWrapper"]/div[1]/h1')->text();
    }

    /**
     * @return int
     */
    public function getMembersCount() : int
    {
        return (int) Parser::removeChildNodes(
            $this->crawler
                ->filterXPath('//div[@id="content"]/table/tr/td[2]/div/div[4]')
        )->text();
    }

    /**
     * @return int
     */
    public function getPicturesCount(): int
    {
        return (int) Parser::removeChildNodes(
            $this->crawler
                ->filterXPath('//div[@id="content"]/table/tr/td[2]/div/div[5]')
        )->text();
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        $category = JString::cleanse(
            Parser::removeChildNodes(
                $this->crawler
                    ->filterXPath('//div[@id="content"]/table/tr/td[2]/div/div[6]')
            )->text()
        );

        return strtolower($category);
    }

    /**
     * @return \DateTimeImmutable
     * @throws \Exception
     */
    public function getCreated(): \DateTimeImmutable
    {
        $node = $this->crawler
            ->filterXPath('//div[@id="content"]/table/tr/td[2]/div/div[contains(., "Created")]');

        $date = JString::cleanse(
            Parser::removeChildNodes($node)
                ->text()
        );

        return new \DateTimeImmutable($date, new \DateTimeZone('UTC'));
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        $typeNode = JString::cleanse(
            $this->crawler
                ->filterXPath('//div[@id="content"]/table/tr/td[2]/div')
                ->text()
        );

        preg_match('~This is a (.*?) club.~', $typeNode, $type);

        return $type[1];
    }

    /**
     * @return array
     */
    public function getAnimeRelations(): array
    {
        $relationsNode = $this->crawler
            ->filterXPath('//div[text()="Anime Relations" and @class="normal_header"]');

        if (!$relationsNode->count()) {
            return [];
        }

        $relationsNode = $relationsNode->nextAll()
            ->filterXPath('//a')
            ->each(
                function (Crawler $crawler) {
                    $relation = $crawler->attr('href');

                    if (preg_match('~anime/(\d+)~', $relation)) {
                        return $crawler;
                    }
                }
            );

        foreach ($relationsNode as $relation) {
            if ($relation instanceof Crawler) {
                $relations[] = new MalUrl(
                    $relation->text(),
                    Constants::BASE_URL . '/' . $relation->attr('href')
                );
            }
        }

        return $relations ?? [];
    }

    /**
     * @return array
     */
    public function getMangaRelations(): array
    {
        $relationsNode = $this->crawler
            ->filterXPath('//div[text()="Manga Relations" and @class="normal_header"]');

        if (!$relationsNode->count()) {
            return [];
        }

        $relationsNode = $relationsNode->nextAll()
            ->filterXPath('//a')
            ->each(
                function (Crawler $crawler) {
                    $relation = $crawler->attr('href');

                    if (preg_match('~manga/(\d+)~', $relation)) {
                        return $crawler;
                    }
                }
            );

        foreach ($relationsNode as $relation) {
            if ($relation instanceof Crawler) {
                $relations[] = new MalUrl(
                    $relation->text(),
                    Constants::BASE_URL . '/' . $relation->attr('href')
                );
            }
        }

        return $relations ?? [];
    }

    /**
     * @return array
     */
    public function getCharacterRelations(): array
    {
        $relationsNode = $this->crawler
            ->filterXPath('//div[text()="Character Relations" and @class="normal_header"]');

        if (!$relationsNode->count()) {
            return [];
        }

        $relationsNode = $relationsNode->nextAll()
            ->filterXPath('//a')
            ->each(
                function (Crawler $crawler) {
                    $relation = $crawler->attr('href');

                    if (preg_match('~character/(\d+)~', $relation)) {
                        return $crawler;
                    }
                }
            );

        foreach ($relationsNode as $relation) {
            if ($relation instanceof Crawler) {
                $relations[] = new MalUrl(
                    $relation->text(),
                    Constants::BASE_URL . '/' . $relation->attr('href')
                );
            }
        }

        return $relations ?? [];
    }

    /**
     * @return array
     */
    public function getStaff(): array
    {
        $staffNode = $this->crawler
            ->filterXPath('//div[contains(text(), "Club Admins and Officers") and @class="normal_header"]')
            ->nextAll()
            ->filterXPath('//a');

        if (!$staffNode->count()) {
            return [];
        }

        $staffNode = $staffNode
            ->each(
                function (Crawler $crawler) {
                    $relation = $crawler->attr('href');

                    if (preg_match('~/profile/(.*?)~', $relation)) {
                        return $crawler;
                    }
                }
            );

        foreach ($staffNode as $staffMember) {
            if ($staffMember instanceof Crawler) {
                $staff[] = UserMetaBasic::fromMeta(
                    $staffMember->text(),
                    Constants::BASE_URL . $staffMember->attr('href')
                );
            }
        }

        return $staff ?? [];
    }
}
