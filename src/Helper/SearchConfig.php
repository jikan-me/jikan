<?php

namespace Jikan\Helper;

class SearchConfig {

	private $type;
	private $subtype;
	private $score;
	private $status;
	private $rated;
	private $startDate = [];
	private $endDate = [];
	private $genreInclude = true; // Exclude = false
	private $genre = [];

	const $validTypes = [ANIME, MANGA];
	const $validSubTypes = [TV, OVA, MOVIE, SPECIAL, ONA, MUSIC, MANGA, NOVEL, ONE_SHOT, DOUJINSHI, MANHWA, MANHUA, OEL];
	const $validStatus = [AIRING, FINISHED_AIRING, TO_BE_AIRED, PUBLISHING, FINISHED_PUBLISHING, TO_BE_PUBLISHED];
	const $validRating = [G, PG, PG13, R17, R, RX];
	const $validGenre = [ACTION, ADVENTURE, CARS, COMEDY, DEMENTIA, DEMONS, MYSTERY, DRAMA, ECCHI, FANTASY, GAME, HENTAI, HISTORICAL, HORROR, KIDS, MAGIC, MARTIAL_ARTS, MECHA, MUSIC, PARODY, SAMURAI, ROMANCE, SCHOOL, SCI_FI, SHOUJO, SHOUJO_AI, SHOUNEN, SHOUNEN_AI, SPACE, SPORTS, SUPER_POWER, VAMPIRE, YAOI, YURI, HAREM, SLICE_OF_LIFE, SUPERNATURAL, MILITARY, POLICE, PSYCHOLOGICAL, THRILLER, SEINEN, JOSEI];

	public function __construct($type) {
		$this->type = $type;
		
		return $this;
	}

	public function buildQuery() {
		return "";
	}

	public function setSubType($subType) {
		$this->subType = in_array($subType, $validSubTypes) ? $subType : 0;

		return $this;
	}

	public function setScore($score) {
		$this->score = $score;

		return $this;
	}

	public function setStatus($status) {
		$this->status = in_array($status, $validStatus) ? $status : 0;

		return $this;
	}

	public function setRated($rated) {
		$this->rated = in_array($rated, $validRating) ? $rated : 0;

		return $this;
	}

	public function setStartDate($day, $month, $year) {
		$this->startDate = [$day, $month, $year];

		return $this;
	}

	public function setEndDate($day, $month, $year) {
		$this->endDate = [$day, $month, $year];

		return $this;
	}

	public function setGenreInclude(Bool $bool) {
		$this->genreInclude = $bool;

		return $this;
	}

	public function setGenre($genre) {
		if (is_array($genre)) {
			$this->genre = array_unique(array_merge($this->genre, $genre));
		} else {
			$this->genre[] = $genre;
			$this->genre = array_unique($this->genre);
		}

		return $this;
	}

}