<?php

namespace Jikan\Helper;

class SearchConfig {

	private $type;
	private $subType = 0;
	private $score = 0;
	private $status = 0;
	private $rated = 0;
	private $startDate = [0,0,0];
	private $endDate = [0,0,0];
	private $genreInclude = true; // Exclude = false
	private $genre = [];

	const VALID_TYPES = [ANIME, MANGA];
	const VALID_SUB_TYPES = [TYPE_TV, TYPE_OVA, TYPE_MOVIE, TYPE_SPECIAL, TYPE_ONA, TYPE_MUSIC, TYPE_MANGA, TYPE_NOVEL, TYPE_ONE_SHOT, TYPE_DOUJINSHI, TYPE_MANHWA, TYPE_MANHUA, TYPE_OEL];
	const VALID_STATUS = [AIRING, FINISHED_AIRING, TO_BE_AIRED, PUBLISHING, FINISHED_PUBLISHING, TO_BE_PUBLISHED];
	const VALID_RATING = [G, PG, PG13, R17, R, RX];
	const VALID_GENRE = [ACTION, ADVENTURE, CARS, COMEDY, DEMENTIA, DEMONS, MYSTERY, DRAMA, ECCHI, FANTASY, GAME, HENTAI, HISTORICAL, HORROR, KIDS, MAGIC, MARTIAL_ARTS, MECHA, MUSIC, PARODY, SAMURAI, ROMANCE, SCHOOL, SCI_FI, SHOUJO, SHOUJO_AI, SHOUNEN, SHOUNEN_AI, SPACE, SPORTS, SUPER_POWER, VAMPIRE, YAOI, YURI, HAREM, SLICE_OF_LIFE, SUPERNATURAL, MILITARY, POLICE, PSYCHOLOGICAL, THRILLER, SEINEN, JOSEI];

	public function __construct($type) {
		if (!in_array($type, self::VALID_TYPES)) {
			throw new \Exception("Failed to build Search Config, unsupported type. (Supported: ANIME, MANGA)");
		}
		$this->type = $type;

		return $this;
	}

	public function build() {
		$query = "";
		// Type
		$query .= "type=".$this->subType;
		// Score
		$query .= "&score=".$this->score;
		// Status
		$query .= "&status=".$this->status;
		// Rated
		$query .= "&r=".$this->rated;
		// Start Date ISO8601
		$query .= "&sd=".$this->startDate[0];
		$query .= "&sm=".$this->startDate[1];
		$query .= "&sy=".$this->startDate[2];
		// End Date ISO8601
		$query .= "&ed=".$this->endDate[0];
		$query .= "&em=".$this->endDate[1];
		$query .= "&ey=".$this->endDate[2];
		// Genre Include
		$query .= "&gx=".($this->genreInclude ? "0" : "1");
		// Genre
		if (!empty($this->genre)) {
			foreach ($this->genre as $key => $genre) {
				$query .= "&genre[]=".$genre;
			}
		}

		return $query;
	}

	public function setType($subType) {
		$this->subType = in_array($subType, self::VALID_SUB_TYPES) ? $subType : 0;

		return $this;
	}

	public function setScore($score) {
		$this->score = $score;

		return $this;
	}

	public function setStatus($status) {
		$this->status = in_array($status, self::VALID_STATUS) ? $status : 0;

		return $this;
	}

	public function setRated($rated) {
		$this->rated = in_array($rated, self::VALID_RATING) ? $rated : 0;

		return $this;
	}

	public function setStartDate($day, $month, $year) {
		$this->startDate = [
			ltrim((string)$year, '0'), 
			ltrim((string)$month, '0'), 
			ltrim((string)$day, '0')
		];

		return $this;
	}

	public function setEndDate($day, $month, $year) {
		$this->endDate = [
			ltrim((string)$year, '0'), 
			ltrim((string)$month, '0'), 
			ltrim((string)$day, '0')
		];

		return $this;
	}

	public function setGenreInclude(Bool $bool) {
		$this->genreInclude = $bool;

		return $this;
	}

	public function setGenre(...$genre) {
		if (is_array($genre)) {
			$this->genre = !empty($genre) ? array_unique(array_merge($this->genre, $genre)) : [];
		} else {
			$this->genre[] = $genre;
			$this->genre = array_unique($this->genre);
		}

		return $this;
	}

}