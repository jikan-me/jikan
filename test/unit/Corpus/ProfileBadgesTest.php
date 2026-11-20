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
        // The (string) casts in the parser are load-bearing: Crawler::attr() is ?string and
        // JString::cleanse() takes string, so a tile without its attributes must come out as an
        // empty badge, not a TypeError.
        $html = '<div class="user-badges">'
            . '<div class="user-badge"><span class="text">nameless</span></div>'
            . '</div>';
        $crawler = new Crawler(null, 'https://myanimelist.net/profile/Kineta');
        $crawler->addHtmlContent($html);

        $badges = (new UserProfileParser($crawler))->getBadges();

        self::assertCount(1, $badges);
        self::assertSame('', $badges[0]->getName());
        self::assertSame('', $badges[0]->getImageUrl());
    }

    #[Test]
    public function a_profile_without_badges_is_an_empty_list(): void
    {
        $profile = (new UserProfileParser(Variants::crawler('user-profile-sandshark')))->getModel();

        self::assertSame([], $profile->getBadges());
    }
}
