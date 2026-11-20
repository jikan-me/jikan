<?php

namespace JikanTest\Corpus;

use Jikan\Model\User\ProfileBadge;
use Jikan\Parser\User\Profile\UserProfileParser;
use JikanTest\TestCase;
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
    public function a_profile_without_badges_is_an_empty_list(): void
    {
        $profile = (new UserProfileParser(Variants::crawler('user-profile-sandshark')))->getModel();

        self::assertSame([], $profile->getBadges());
    }
}
