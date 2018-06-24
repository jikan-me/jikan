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
 * Request
 */
define( 'CHARACTERS_STAFF' , 'characters' );
define( 'CHARACTERS' , 'characters' );
define( 'EPISODES' , 'episodes' );
define( 'NEWS' , 'news' );
define( 'VIDEOS' , 'video' );
define( 'STATS' , 'stats' );
define( 'PICTURES' , 'pics' );
define( 'FORUM' , 'forum' );
define( 'MORE_INFO' , 'moreinfo' );

define( 'ANIME' , 'Anime' );
define( 'MANGA' , 'Manga' );
define( 'CHARACTER' , 'Character' );
define( 'PERSON' , 'Person' );
define( 'PEOPLE', 'Person'); // alias

define( 'SEASON_WINTER' , 'winter' );
define( 'SEASON_SPRING' , 'spring' );
define( 'SEASON_SUMMER' , 'summer' );
define( 'SEASON_FALL' , 'fall' );

/*
 * Search
 */

// GENRE
define('GENRE_ACTION', 1);
define('GENRE_ADVENTURE', 2);
define('GENRE_CARS', 3);
define('GENRE_COMEDY', 4);
define('GENRE_DEMENTIA', 5);
define('GENRE_DEMONS', 6);
define('GENRE_MYSTERY', 7);
define('GENRE_DRAMA', 8);
define('GENRE_ECCHI', 9);
define('GENRE_FANTASY', 10);
define('GENRE_GAME', 11);
define('GENRE_HENTAI', 12);
define('GENRE_HISTORICAL', 13);
define('GENRE_HORROR', 14);
define('GENRE_KIDS', 15);
define('GENRE_MAGIC', 16);
define('GENRE_MARTIAL_ARTS', 17);
define('GENRE_MECHA', 18);
define('GENRE_MUSIC', 19);
define('GENRE_PARODY', 20);
define('GENRE_SAMURAI', 21);
define('GENRE_ROMANCE', 22);
define('GENRE_SCHOOL', 23);
define('GENRE_SCI_FI', 24);
define('GENRE_SHOUJO', 25);
define('GENRE_SHOUJO_AI', 26);
define('GENRE_SHOUNEN', 27);
define('GENRE_SHOUNEN_AI', 28);
define('GENRE_SPACE', 29);
define('GENRE_SPORTS', 30);
define('GENRE_SUPER_POWER', 31);
define('GENRE_VAMPIRE', 32);
define('GENRE_YAOI', 33);
define('GENRE_YURI', 34);
define('GENRE_HAREM', 35);
define('GENRE_SLICE_OF_LIFE', 36);
define('GENRE_SUPERNATURAL', 37);
define('GENRE_MILITARY', 38);
define('GENRE_POLICE', 39);
define('GENRE_PSYCHOLOGICAL', 40);
define('GENRE_THRILLER', 41);
define('GENRE_SEINEN', 42);
define('GENRE_JOSEI', 43);
define('GENRE_DOUJINSHI', 43);
define('GENRE_GENDER_BENDER', 44);

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
define('STATUS_AIRING', 1);
define('STATUS_FINISHED_AIRING', 2);
define('STATUS_TO_BE_AIRED', 3);

define('STATUS_PUBLISHING', 1);
define('STATUS_FINISHED_PUBLISHING', 2);
define('STATUS_TO_BE_PUBLISHED', 3);

// Rating
define('RATING_G', 1); //  All Ages
define('RATING_PG', 2); // Children
define('RATING_PG13', 2); // Teens 13 or older
define('RATING_R17', 4); // Violence & Profanity
define('RATING_R', 5); // Mild Nudity
define('RATING_RX', 6); // Hentai

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

/*
 * User
 */
define('USER_PROFILE', 'user_profile');
define('USER_HISTORY', 'user_history');
define('USER_FRIENDS', 'user_friends');

/*
 * User List
 */
define('USER_LIST_ALL', 'user_list_all');
define('USER_LIST_WATCHING', 'user_list_watching');
define('USER_LIST_COMPLETED', 'user_list_completed');
define('USER_LIST_ONHOLD', 'user_list_onhold');
define('USER_LIST_DROPPED', 'user_list_dropped');
define('USER_LIST_PLAN_TO_WATCH', 'user_list_plan_to_watch');
define('USER_LIST_PTW', 'user_list_plan_to_watch');