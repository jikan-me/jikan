# Upgrading from v4 to v5

For code on `jikan-me/jikan ^4`. v5 is currently a pre-release (`5.0.0-beta.3`); this guide is for moving to it and to `5.0.0` when that ships. For the complete list of changes, including fixes that only affected earlier v5 pre-releases, read the [changelog](CHANGELOG.md).

**In short:** `MalClient` and all its existing methods and request classes still work as before. What changes is the PHP version, the HTTP libraries underneath, a handful of removed classes, and many getters that can now return `null` where they used to throw.

**How this was checked.** The difference between the two public APIs was computed from the source of `v4.0.13` and v5. Then `v4.0.13` and v5 were both run against the same 85 recorded MyAnimeList pages covering the endpoints they share. 51 pages produce identical results. Every real difference in the rest is listed below; the only ones left out are relative dates such as "6 minutes ago", which move with the clock.

## 1. Requirements

| | v4 | v5 |
|---|---|---|
| PHP | 8.0 or later | **8.4.4 or later** |
| HTTP layer | `fabpot/goutte` (abandoned) | Symfony components (`http-client`, `browser-kit`, `dom-crawler`, `css-selector`, `clock`, all `^8`) |

PHP 8.4.0–8.4.3 are refused on purpose. They have a bug in PHP's own HTML5 parser that can corrupt emoji and rare characters, which makes `json_encode()` fail on a model.

```bash
composer require jikan-me/jikan:^5.0@beta   # "@beta" until a stable 5.0.0 exists
composer update                              # also removes fabpot/goutte
```

Passing your own HTTP client works exactly as before: `new MalClient($client)` takes a `Symfony\Contracts\HttpClient\HttpClientInterface`.

## 2. Code you may need to change

| If your code... | Do this |
|---|---|
| calls `getLicensors()` on an anime card (genre, producer, seasonal lists) | It's removed. MyAnimeList no longer shows licensors on cards and v4 returned `[]`. Read `Anime::getLicensors()` from `getAnime()`. |
| type-hints `Model\News\NewsList` or `NewsListItem` for the result of `getNewsList()` | It now returns `Model\News\ResourceNewsList` of `ResourceNewsListItem`. The getters have the same names. `getImages()` returns a `NewsImageResource`; `getJpg()->getImageUrl()` works as before. |
| uses `Jikan\Goutte\GoutteWrapper` | Replaced by `Jikan\Http\HttpClientWrapper`, same `request()` method. It no longer records browser history unless you ask. |
| uses `Model\Manga\MangaReviewer`, `Parser\Manga\MangaReviewerParser` or `MangaReviewScoresParser` | Removed. They extended classes that no longer existed, so they could never be loaded. |
| calls `AnimeReviewScores::fromParser()` or `MangaReviewScores::fromParser()` | Use `fromOverall(int $rating)`. See the review score note in section 3. |
| uses `Constants::SEARCH_MANGA_NOVEL` | Its value is now `8`. It was `2`, which MyAnimeList now treats as Light Novel. Use `Constants::SEARCH_MANGA_LIGHT_NOVEL` (`2`) if that's what you meant. |
| catches `\TypeError` around client calls to cope with missing fields | Stop. Those cases now return `null` (section 3) or throw `ParserException`. |
| calls parser classes directly | `NewsListItemParser::getImage()` and `getIntro()` are removed. |

## 3. Getters that can now return `null`

In v4, a missing field often meant a `TypeError`. These getters are now nullable instead, so **handle `null`**. Code that ran against pages that have the field sees no difference.

| Area | Getters |
|---|---|
| Images | Every image URL on the meta and list models, and the image resource getters (`getJpg()->getImageUrl()` and so on), are `null` when MyAnimeList shows its "no image" placeholder. That includes the default avatar of a user without a picture. Empty strings in these fields are now `null` too. A site icon is no longer returned as a cover. |
| Anime | `Anime::getDuration()`; on anime cards `getType()` and `getMembers()` |
| Episodes | `EpisodeListItem::getScore()`, `getUrl()`, `getForumUrl()`; `StreamEpisodeListItem::getMalId()` |
| Lists | `getMalId()` on `AnimeGenreListItem`, `MangaGenreListItem`, `MagazineListItem` and `ProducerListItem` |
| News | `NewsListItem::getDate()` |
| People | `Person::getMemberFavorites()`, `VoiceActingRole::getRole()` |
| Users | `Friend::getFriendsSince()`, `Profile::getLocation()`, `UserSearchListItem::getLastOnline()`, `UserReviews::getLastVisiblePage()`. A profile's "about" is `null` instead of `""` when empty. |
| User statistics | `AnimeStats`: `getDaysWatched()`, `getEpisodesWatched()`, `getMeanScore()`, `getRewatched()`, `getTotalEntries()`. `MangaStats`: `getChaptersRead()`, `getDaysRead()`, `getMeanScore()`, `getReread()`, `getTotalEntries()`, `getVolumesRead()` |
| Links | `MalUrl::getUrl()`. `MalUrl::getMalId()` can be a string for tag links (`int|string`). |

**Review scores.** `getAnimeScores()` and `getMangaScores()` never worked in v4. They work now, but MyAnimeList only shows one "Reviewer's Rating" on reviews today, so `getOverall()` is that rating and the category getters (`getStory()`, `getAnimation()`, `getArt()`, `getSound()`, `getCharacter()`, `getEnjoyment()`) return `null`. `getHelpfulCount()` is a review's total reactions.

## 4. Results that are different

These don't change a signature, but they change what you get back. Each one was seen when running both versions on the same pages.

**Errors and empty results**
- **Search with no results** returns an empty result for anime, manga and person search. v4 threw `BadResponseException` (anime, manga) or `ParserException` (person).
- **Other HTTP errors** (429, 500) from character and person search reach you as `BadResponseException`. In v4 they ended as a `TypeError`.
- **`getAnimeSearchAlt()`** works. In v4 it threw a `TypeError` for its return type.

**Lists and pagination**
- **Genre lists** report `hasNextPage()` and `getLastVisiblePage()`. In v4 they were always `false` and `1`, though a genre spans many pages (20 for Action).
- **"Recently updated by users"** (anime and manga) reports `hasNextPage()`. MyAnimeList only links the next page, so `getLastVisiblePage()` is the next page when there is one.
- **A user's reviews** report `hasNextPage()` from the "More Reviews" link. v4 always said `true`.
- **`getMangaGenres()`** returned its sections shifted by one (empty `genres`, themes under explicit genres) on the current page layout. They are in the right places now.

**Fields that were empty and are now filled**
- Anime cards on genre, producer, seasonal and schedule pages have a `type`. Genre and producer cards also have `getAiringStart()`. In v4 these were `null`.
- Manga cards on genre and magazine pages have `getAuthors()` and `getSerialization()`.
- Top characters and top people have `getNameKanji()`.
- Review lists have `getEpisodesWatched()` or `getChaptersRead()`, and a profile's last updates keep the episode or chapter count when the total is not known yet ("92/?"). A last update with nothing read yet ("On-Hold -/355") gives status `On-Hold`, progress 0 and total 355.
- Episode titles no longer end with a trailing space.

**Dates**
- **UTC.** A single episode's air date is UTC midnight of the printed day, the same as in the episode list. In v4 it was midnight in Japan (`+09:00`).
- **Two-digit years** are read in the right century: "Kaguya-hime" (1961) would be 2061 with plain PHP date parsing.
- **Relative dates** ("Yesterday", "5 hours ago") are resolved against a PSR-20 clock, which is the system clock by default. In your own tests you can pin it with `Symfony\Component\Clock\Clock::set(new MockClock(...))`.
- **Card start dates** (`getAiringStart()` on anime cards from genre, producer, seasonal and schedule pages) are UTC midnight of the day shown. Where v4 had a value it was midnight in Japan converted to UTC, 9 hours earlier, so its date part was the day before. A year-only date ("2027") gives 1 January of that year; in v4 it gave the current date and time.

**Other**
- **Repeatable parsing.** Calling `getModel()` twice, or reading getters in a different order, failed with "The current node list is empty" on `AnimeParser` and other parsers in v4. It works.
- **A card with no member count** gives `null` from `getMembers()`.

## 5. What's new

- 11 content endpoints: `getArticle`, `getArticleSearch`, `getArticleTags`, `getArticlesByTag`, `getNews`, `getNewsByTag`, `getNewsSearch`, `getNewsTags`, `getPinnedArticles`, `getRecentArticles` and `getRecentNews`, each with a matching `Jikan\Request\*` class.
- New search constants for the anime types CM, PV and TV Special, the manga type Light Novel, and the manga statuses On Hiatus and Discontinued.

## 6. Checklist

- [ ] PHP is 8.4.4 or later everywhere the library runs (CI, containers, hosts).
- [ ] `composer update` ran and `fabpot/goutte` is gone from `composer.lock`.
- [ ] No references to the removed classes and methods in section 2.
- [ ] Every use of a getter listed in section 3 copes with `null`. A static analyser (PHPStan or Psalm) finds these quickly after the upgrade, because the return types changed.
- [ ] Code that depended on an exception for "no results" now checks for an empty result.
- [ ] Tests that compare dates expect UTC, and tests that pin a card's type or date expect the new values.

Something missing here? Open an issue at [jikan-me/jikan](https://github.com/jikan-me/jikan/issues).
