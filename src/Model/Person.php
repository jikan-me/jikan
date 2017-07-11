<?php

namespace Jikan\Model;

use Jikan\Model\Model as Model;

class Anime extends Model
{

	public $link_canonical;

	public $given_name;

	public $family_name;

	public $alternate_name;

	public $birthday;

	public $website;

	public $member_favorites;

	public $more;

	public $image_url;

	public $voice_acting_role = [];

	public $anime_staff_position = [];

	public $published_manga = [];

}