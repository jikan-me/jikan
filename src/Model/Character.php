<?php

namespace Jikan\Model;

use Jikan\Model\Model as Model;

class Character extends Model
{

	public $name;

	public $name_japanese;

	public $about;

	public $member_favorites;

	public $image_url;

	public $animeography = [];

	public $mangaography = [];

	public $voice_actors = [];

}