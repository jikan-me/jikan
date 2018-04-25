<?php

/*
 * URL & Endpoints
 */
define( 'BASE_URL' , 'https://myanimelist.net/' );

define( 'ANIME_ENDPOINT' , 'anime/' );
define( 'MANGA_ENDPOINT' , 'manga/' );
define( 'PEOPLE_ENDPOINT' , 'people/' );
define( 'CHARACTER_ENDPOINT' , 'character/' );
define( 'USER_LIST_ENDPOINT' , 'malappinfo.php');


/*
 * Extend Flags
 */
define( 'CHARACTERS_STAFF' , 'charactersStaff' );
define( 'CHARACTERS' , 'characters' );
define( 'EPISODES' , 'episodes' );
define( 'NEWS' , 'news' );
define( 'VIDEOS' , 'videos' );
define( 'STATS' , 'stats' );
define( 'PICTURES' , 'pictures' );
define( 'FORUM' , 'forum' );
define( 'MORE_INFO' , 'moreInfo' );

define( 'ANIME' , 'anime' );
define( 'MANGA' , 'manga' );
define( 'CHARACTER' , 'character' );
define( 'PERSON' , 'person' );
define( 'PEOPLE', 'person'); // alias

define( 'WINTER' , 'winter' );
define( 'SPRING' , 'spring' );
define( 'SUMMER' , 'summer' );
define( 'FALL' , 'fall' );

/*
 * Search
 */

// GENRE
define('ACTION', 1);
define('ADVENTURE', 2);
define('CARS', 3);
define('COMEDY', 4);
define('DEMENTIA', 5);
define('DEMONS', 6);
define('MYSTERY', 7);
define('DRAMA', 8);
define('ECCHI', 9);
define('FANTASY', 10);
define('GAME', 11);
define('HENTAI', 12);
define('HISTORICAL', 13);
define('HORROR', 14);
define('KIDS', 15);
define('MAGIC', 16);
define('MARTIAL_ARTS', 17);
define('MECHA', 18);
define('MUSIC', 19);
define('PARODY', 20);
define('SAMURAI', 21);
define('ROMANCE', 22);
define('SCHOOL', 23);
define('SCI_FI', 24);
define('SHOUJO', 25);
define('SHOUJO_AI', 26);
define('SHOUNEN', 27);
define('SHOUNEN_AI', 28);
define('SPACE', 29);
define('SPORTS', 30);
define('SUPER_POWER', 31);
define('VAMPIRE', 32);
define('YAOI', 33);
define('YURI', 34);
define('HAREM', 35);
define('SLICE_OF_LIFE', 36);
define('SUPERNATURAL', 37);
define('MILITARY', 38);
define('POLICE', 39);
define('PSYCHOLOGICAL', 40);
define('THRILLER', 41);
define('SEINEN', 42);
define('JOSEI', 43);
define('DOUJINSHI', 43);
define('GENDER_BENDER', 44);

// Type
define('TYPE_TV', 1);
define('TYPE_OVA', 2);
define('TYPE_MOVIE', 3);
define('TYPE_SPECIAL', 4);
define('TYPE_ONA', 5);
define('TYPE_MUSIC', 6);

define('TYPE_MANGA', 1);
define('TYPE_NOVEL', 2);
define('TYPE_ONE_SHOT', 3);
define('TYPE_DOUJINSHI', 4);
define('TYPE_MANHWA', 5);
define('TYPE_MANHUA', 6);
define('TYPE_OEL', 7);

// Status
define('AIRING', 1);
define('FINISHED_AIRING', 2);
define('TO_BE_AIRED', 3);

define('PUBLISHING', 1);
define('FINISHED_PUBLISHING', 2);
define('TO_BE_PUBLISHED', 3);

// Rating
define('G', 1); //  All Ages
define('PG', 2); // Children
define('PG13', 2); // Teens 13 or older
define('R17', 4); // Violence & Profanity
define('R', 5); // Mild Nudity
define('RX', 6); // Hentai

/*
 * Top Anime/Manga
 */

// Anime
define('TOP_AIRING', 'airing');
define('TOP_UPCOMING', 'upcoming');
define('TOP_TV', 'tv');
define('TOP_MOVIE', 'movie');
define('TOP_OVA', 'ova');
define('TOP_SPECIAL', 'special');

// Manga
define('TOP_MANGA', 'manga');
define('TOP_NOVEL', 'novels');
define('TOP_ONE_SHOT', 'oneshots');
define('TOP_DOUJINSHI', 'doujin');
define('TOP_MANHWA', 'manhwa');
define('TOP_MANHUA', 'manhua');

// Both
define('TOP_POPULARITY', 'bypopularity');
define('TOP_FAVORITE', 'favorite');