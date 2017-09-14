<?php

namespace Jikan\Model;


class Anime extends Model
{

	public $link_canonical;

	public $title;

	public $title_english;

	public $title_japanese;

	public $title_synonyms;

	public $image_url;

	public $type;

    public $source;

    public $episodes;

	public $status;

	public $aired;

	public $duration;

	public $rating;

	public $score;

	public $scored_by;

	public $rank;

	public $popularity;

	public $members;

	public $favorites;

	public $synopsis;

	public $background;

	public $related = [];

	public $premiered = [];

	public $broadcast = [];

	public $producer = [];

	public $licensor = [];

	public $studio = [];

	public $genre = [];

	public $opening_theme = [];

	public $ending_theme = [];

}