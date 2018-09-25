<?php

namespace Jikan\Helper;

/**
 * Keep constants here
 *
 * Class Constants
 *
 * @package Jikan\Helper
 */
class Constants
{
    public const BASE_URL = 'https://myanimelist.net';

    public const SEASONS = [
        'Winter',
        'Spring',
        'Summer',
        'Fall',
    ];

    public const WINTER = 'winter';
    public const SPRING = 'spring';
    public const SUMMER = 'summer';
    public const FALL = 'fall';

    public const ANIME = 'anime';
    public const MANGA = 'manga';
    public const CHARACTER = 'character';
    public const PERSON = 'person';

    public const TOP_AIRING = 'airing';
    public const TOP_UPCOMING = 'upcoming';
    public const TOP_TV = 'tv';
    public const TOP_MOVIE = 'movie';
    public const TOP_OVA = 'ova';
    public const TOP_SPECIAL = 'special';

    public const TOP_MANGA = 'manga';
    public const TOP_NOVEL = 'novels';
    public const TOP_ONE_SHOT = 'oneshots';
    public const TOP_DOUJINSHI = 'doujin';
    public const TOP_MANHWA = 'manhwa';
    public const TOP_MANHUA = 'manhua';

    public const TOP_BY_POPULARITY = 'bypopularity';
    public const TOP_BY_FAVORITES = 'favorite';

    public const SEARCH_ANIME_TV = 1;
    public const SEARCH_ANIME_OVA = 2;
    public const SEARCH_ANIME_MOVIE = 3;
    public const SEARCH_ANIME_SPECIAL = 4;
    public const SEARCH_ANIME_ONA = 5;
    public const SEARCH_ANIME_MUSIC = 6;

    public const SEARCH_MANGA_MANGA = 1;
    public const SEARCH_MANGA_NOVEL = 2;
    public const SEARCH_MANGA_ONESHOT = 3;
    public const SEARCH_MANGA_DOUJIN = 4;
    public const SEARCH_MANGA_MANHWA = 5;
    public const SEARCH_MANGA_MANHUA = 6;

    public const SEARCH_ANIME_STATUS_AIRING = 1;
    public const SEARCH_ANIME_STATUS_FINISHED_AIRING = 2;
    public const SEARCH_ANIME_STATUS_COMPLETED = 2; // alias
    public const SEARCH_ANIME_STATUS_TO_BE_AIRD = 3;
    public const SEARCH_ANIME_STATUS_TBA = 3; // alias

    public const SEARCH_MANGA_STATUS_PUBLISHING = 1;
    public const SEARCH_MANGA_STATUS_FINISHED_PUBLISHING = 2;
    public const SEARCH_MANGA_STATUS_COMPLETED = 2; // alias
    public const SEARCH_MANGA_STATUS_TO_BE_PUBLISHED = 3;
    public const SEARCH_MANGA_STATUS_TBP = 3; // alias

    public const SEARCH_ANIME_RATING_G = 1;
    public const SEARCH_ANIME_RATING_ALL = 1; // alias
    public const SEARCH_ANIME_RATING_PG = 2;
    public const SEARCH_ANIME_RATING_PG13 = 3;
    public const SEARCH_ANIME_RATING_R17 = 4;
    public const SEARCH_ANIME_RATING_R = 5;
    public const SEARCH_ANIME_RATING_RX = 6;
    public const SEARCH_ANIME_RATING_HENTAI = 6; // alias

    public const GENRE_ANIME_ACTION = 1;
    public const GENRE_ANIME_ADVENTURE = 2;
    public const GENRE_ANIME_CARS = 3;
    public const GENRE_ANIME_COMEDY = 4;
    public const GENRE_ANIME_DEMENTIA = 5;
    public const GENRE_ANIME_DEMONS = 6;
    public const GENRE_ANIME_MYSTERY = 7;
    public const GENRE_ANIME_DRAMA = 8;
    public const GENRE_ANIME_ECCHI = 9;
    public const GENRE_ANIME_FANTASY = 10;
    public const GENRE_ANIME_GAME = 11;
    public const GENRE_ANIME_HENTAI = 12;
    public const GENRE_ANIME_HISTORICAL = 13;
    public const GENRE_ANIME_HORROR = 14;
    public const GENRE_ANIME_KIDS = 15;
    public const GENRE_ANIME_MAGIC = 16;
    public const GENRE_ANIME_MARTIAL_ARTS = 17;
    public const GENRE_ANIME_MECHA = 18;
    public const GENRE_ANIME_MUSIC = 19;
    public const GENRE_ANIME_PARODY = 20;
    public const GENRE_ANIME_SAMURAI = 21;
    public const GENRE_ANIME_ROMANCE = 22;
    public const GENRE_ANIME_SCHOOL = 23;
    public const GENRE_ANIME_SCI_FI = 24;
    public const GENRE_ANIME_SHOUJO = 25;
    public const GENRE_ANIME_SHOUJO_AI = 26;
    public const GENRE_ANIME_SHOUNEN = 27;
    public const GENRE_ANIME_SHOUNEN_AI = 28;
    public const GENRE_ANIME_SPACE = 29;
    public const GENRE_ANIME_SPORTS = 30;
    public const GENRE_ANIME_SUPER_POWER = 31;
    public const GENRE_ANIME_VAMPIRE = 32;
    public const GENRE_ANIME_YAOI = 33;
    public const GENRE_ANIME_YURI = 34;
    public const GENRE_ANIME_HAREM = 35;
    public const GENRE_ANIME_SLICE_OF_LIFE = 36;
    public const GENRE_ANIME_SUPERNATURAL = 37;
    public const GENRE_ANIME_MILITARY = 38;
    public const GENRE_ANIME_POLICE = 39;
    public const GENRE_ANIME_PSYCHOLOGICAL = 40;
    public const GENRE_ANIME_THRILLER = 41;
    public const GENRE_ANIME_SEINEN = 42;
    public const GENRE_ANIME_JOSEI = 43;

    public const GENRE_MANGA_ACTION = 1;
    public const GENRE_MANGA_ADVENTURE = 2;
    public const GENRE_MANGA_CARS = 3;
    public const GENRE_MANGA_COMEDY = 4;
    public const GENRE_MANGA_DEMENTIA = 5;
    public const GENRE_MANGA_DEMONS = 6;
    public const GENRE_MANGA_MYSTERY = 7;
    public const GENRE_MANGA_DRAMA = 8;
    public const GENRE_MANGA_ECCHI = 9;
    public const GENRE_MANGA_FANTASY = 10;
    public const GENRE_MANGA_GAME = 11;
    public const GENRE_MANGA_HENTAI = 12;
    public const GENRE_MANGA_HISTORICAL = 13;
    public const GENRE_MANGA_HORROR = 14;
    public const GENRE_MANGA_KIDS = 15;
    public const GENRE_MANGA_MAGIC = 16;
    public const GENRE_MANGA_MARTIAL_ARTS = 17;
    public const GENRE_MANGA_MECHA = 18;
    public const GENRE_MANGA_MUSIC = 19;
    public const GENRE_MANGA_PARODY = 20;
    public const GENRE_MANGA_SAMURAI = 21;
    public const GENRE_MANGA_ROMANCE = 22;
    public const GENRE_MANGA_SCHOOL = 23;
    public const GENRE_MANGA_SCI_FI = 24;
    public const GENRE_MANGA_SHOUJO = 25;
    public const GENRE_MANGA_SHOUJO_AI = 26;
    public const GENRE_MANGA_SHOUNEN = 27;
    public const GENRE_MANGA_SHOUNEN_AI = 28;
    public const GENRE_MANGA_SPACE = 29;
    public const GENRE_MANGA_SPORTS = 30;
    public const GENRE_MANGA_SUPER_POWER = 31;
    public const GENRE_MANGA_VAMPIRE = 32;
    public const GENRE_MANGA_YAOI = 33;
    public const GENRE_MANGA_YURI = 34;
    public const GENRE_MANGA_HAREM = 35;
    public const GENRE_MANGA_SLICE_OF_LIFE = 36;
    public const GENRE_MANGA_SUPERNATURAL = 37;
    public const GENRE_MANGA_MILITARY = 38;
    public const GENRE_MANGA_POLICE = 39;
    public const GENRE_MANGA_PSYCHOLOGICAL = 40;
    public const GENRE_MANGA_THRILLER = 41;
    public const GENRE_MANGA_SEINEN = 42;
    public const GENRE_MANGA_JOSEI = 43;
    public const GENRE_MANGA_DOUJINSHI = 43;
    public const GENRE_MANGA_GENDER_BENDER = 44;

    public const USER_ANIME_LIST_ALL = 7;
    public const USER_ANIME_LIST_WATCHING = 1;
    public const USER_ANIME_LIST_COMPLETED = 2;
    public const USER_ANIME_LIST_ONHOLD = 3;
    public const USER_ANIME_LIST_DROPPED = 4;
    public const USER_ANIME_LIST_PTW = 6;
    public const USER_ANIME_LIST_PLANTOWATCH = 6;

    public const USER_MANGA_LIST_ALL = 7;
    public const USER_MANGA_LIST_READING = 1;
    public const USER_MANGA_LIST_COMPLETED = 2;
    public const USER_MANGA_LIST_ONHOLD = 3;
    public const USER_MANGA_LIST_DROPPED = 4;
    public const USER_MANGA_LIST_PTR = 6;
    public const USER_MANGA_LIST_PLANTOREAD = 6;
}
