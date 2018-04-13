<?php
require_once dirname(__DIR__) . "/vendor/autoload.php";

// init
$jikan = new Jikan\Jikan;


/* 
 * $jikan->Search(query, type[, page, config])
 *
 * `query` : string (must be atleast 3 characters)
 * `type` : CONSTANT [ANIME, MANGA, PEOPLE, PERSON (alias), CHARACTER] 
 * `page` : integer // search result page
 * `config` : Helper\SearchConfig OBJECT
 */

// Basic Search
$jikan->Search('Code Geass', ANIME);
var_dump($jikan->response['result']); // Search results for 'Code Geass' that are Anime
$pages = $jikan->response['result_last_page']; // Number of pages of search results. You can use this for pagination 

/*
 * NOTE: Response is stored in `$jikan->response`
 * Search results are in `$jikan->response['result']` (array)
 * No# of pages `$jikan->response['result_last_page']` (integer)
 */

var_dump(
	$jikan->Search('Sword Art Online', MANGA)->response['result']; // All manga search results for 'Sword Art Online'
);

$jikan->Search('Makise', CHARACTER, 2); // 2nd page of the search results for 'Makise'
$jikan->Search('Sawano', PEOPLE); // you get it by now


/*
 * Advanced Search
 */

// Call the SearchConfig Helper to build the config for the query.
// Only Argument is the SearchConfig Type. Only Legal values are ANIME and MANGA
// There are no advanced search options for CHARACTER or PEOPLE
// ALL CONSTANTS ARE DEFINED IN `src/config.php`
$config = new Jikan\Helper\SearchConfig(ANIME);

// NOTE: If it isn't obvious, All these flags are optional.
// NOTE2: These methods can be chained

/* Types
 * CONSTANTS ARE DEFINED IN `src/config.php`
 */
$config->setType(TYPE_MOVIE);

/* Score (integer) 1-10
 * Minimum Score in results
 */
$config->setScore(5);

/* Status
 * e.g finished, airing, publishing, etc
 * CONSTANTS ARE DEFINED IN `src/config.php`
 */
$config->setStatus(FINISHED_AIRING);

/* Rated
 * PG, PG13, R17, etc
 * CONSTANTS ARE DEFINED IN `src/config.php`
 */
$config->setRated(PG13);

/* Start Date
 * setStartDate(day (int), month (int), year (int))
 */
$config->setStartDate(5, 10, 2004);

/* End Date
 * setEndDate(day (int), month (int), year (int))
 */
$config->setStartDate(27, 3, 2012);

/* Genre
 * CONSTANTS ARE DEFINED IN `src/config.php`
 */

// You can set one by one
$config->setGenre(ACTION);
$config->setGenre(COMEDY);

// or in bulk
$config->setGenre([ACTION, COMEDY, SUPER_POWER, SUPERNATURAL, SHOUNEN]);
// Don't worry about duplicates, they're removed automatically

/* Genre Include Or Exclude
 * The genre that are defined above this
 * You have the option of having the results either
 * - Include anime/manga with them
 * - Exclude anime/manga with them
 *
 * NOTE: THIS IS `TRUE` BY DEFAULT
 */

$config->setGenreInclude(true); // This will Include the results with these genre (default)

$config->setGenreInclude(false); // Exclude

// Pass the object as an argument
$jikan->Search('Bleach', ANIME, 1, $config);

var_dump($jikan->response);
?>