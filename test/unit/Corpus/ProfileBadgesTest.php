<?php

namespace JikanTest\Corpus;

use Jikan\Model\User\ProfileBadge;
use Jikan\Parser\User\Profile\UserProfileParser;
use JikanTest\TestCase;
use Symfony\Component\DomCrawler\Crawler;
use PHPUnit\Framework\Attributes\Test;

/**
 * MyAnimeList renders a user's badge tiles on their profile — `div.user-badges` with one
 * `div.user-badge` per tile, the artwork in `data-bg` and the name in `title`. A profile without
 * badges renders no block at all, which reads as an empty list; jikan#539.
 */
class ProfileBadgesTest extends TestCase
{
    #[Test]
    public function a_profile_with_badges_lists_them(): void
    {
        $profile = (new UserProfileParser(Variants::crawler('user-profile-kineta')))->getModel();

        $badges = $profile->getBadges();
        self::assertNotSame([], $badges);
        self::assertContainsOnlyInstancesOf(ProfileBadge::class, $badges);

        foreach ($badges as $badge) {
            self::assertNotSame('', $badge->getName(), 'A badge tile carries no name.');
            self::assertMatchesRegularExpression(
                '~^https://cdn\.myanimelist\.net/images/badge/.*~',
                $badge->getImageUrl(),
                'A badge tile carries no artwork.'
            );
        }
    }

    #[Test]
    public function a_badge_tile_without_its_attributes_is_an_empty_badge(): void
    {
        // A tile without its attributes comes out as an empty badge, not a TypeError or a null
        // object: the name is an empty string (JString::cleanse on the cast), the artwork is null
        // (ImagePlaceholder::normalize of the absent attribute) - the library's absence rule.
        $html = '<div class="user-badges">'
            . '<div class="user-badge"><span class="text">nameless</span></div>'
            . '</div>';
        $crawler = new Crawler(null, 'https://myanimelist.net/profile/Kineta');
        $crawler->addHtmlContent($html);

        $badges = (new UserProfileParser($crawler))->getBadges();

        self::assertCount(1, $badges);
        self::assertSame('', $badges[0]->getName());
        self::assertNull($badges[0]->getImageUrl());
    }

    #[Test]
    public function placeholder_artwork_is_null(): void
    {
        // The artwork funnels through ImagePlaceholder like every other image getter, so a
        // placeholder asset reads as absence instead of a URL a consumer would render as a "?" gif.
        $html = '<div class="user-badges">'
            . '<div class="user-badge" data-bg="https://cdn.myanimelist.net/r/80x80/images/questionmark_50.gif?s=x"'
            . ' title="Ghost badge"><span class="text">Ghost badge</span></div>'
            . '</div>';
        $crawler = new Crawler(null, 'https://myanimelist.net/profile/Kineta');
        $crawler->addHtmlContent($html);

        $badges = (new UserProfileParser($crawler))->getBadges();

        self::assertCount(1, $badges);
        self::assertSame('Ghost badge', $badges[0]->getName());
        self::assertNull($badges[0]->getImageUrl());
    }

    #[Test]
    public function a_profile_without_badges_is_an_empty_list(): void
    {
        $profile = (new UserProfileParser(Variants::crawler('user-profile-sandshark')))->getModel();

        self::assertSame([], $profile->getBadges());
    }
}
