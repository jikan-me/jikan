<?php

namespace Jikan\Model;

use Jikan\Model\Model as Model;

class Manga extends Model
{

	public $link_canonical;

	public $title;

	public $title;

	public $title_english;

	public $title_synonyms;

	public $title_japanese;

	public $status;

	public $image_url;

	public $type;

	public $volumes;

	public $chapters;

	public $published;

	public $rank;

	public $score;

	public $popularity;

	public $members;

	public $favorites;

	public $synopsis;

	public $background;
	
	public $related = [];

	public $genre = [];

	public $author = []

	public $serialization = [];

}