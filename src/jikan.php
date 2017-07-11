<?
/**
*	Jikan - MyAnimeList Unofficial API @version 1.0.0 beta
*	Developed by Nekomata | irfandahir.com
*	
*	This is an unofficial MAL API that provides the features that the official one lacks.
*	Jikan scraps web pages through a modular method, parses the data you require from them and returns it back as a PHP/JSON array.
*	Therefore, no authentication is needed for fetching anime, manga, character, people, search result data.
*
*	Jikan is in no way affiliated with MyAnimeList.
*/
namespace Jikan;





//use Jikan\Helper\Utils;
//use Jikan\Lib\Parser;
//
//use Jikan\Get\Anime;
//use Jikan\Get\Manga;
//use Jikan\Get\Character;
//use Jikan\Get\Person;
//use Jikan\Get\UserList;


class Jikan
{

	public $Anime;
	public $Manga;
	public $Character;
	public $Person;

	public function __construct() {
		echo "ok";
	}

}
