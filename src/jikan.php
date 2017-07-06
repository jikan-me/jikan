<?php
/**
*	Jikan - MyAnimeList Unofficial API @version 0.3.0 beta
*	Developed by Nekomata | irfandahir.com
*	
*	This is an unofficial MAL API that provides the features that the official one lacks.
*	Jikan scraps web pages through a modular method, parses the data you require from them and returns it back as a PHP/JSON array.
*	Therefore, no authentication is needed for fetching anime, manga, character, people, search result data.
*/

namespace Jikan {

	class Get {

/*
	Base URLs for parsing
*/
		public $types = array(
			"anime" => "https://myanimelist.net/anime/",
			"manga" => "https://myanimelist.net/manga/",
			"people" => "https://myanimelist.net/people/",
			"character" => "https://myanimelist.net/character/",
			"list" => "https://myanimelist.net/malappinfo.php"
		);

/*
	Identifiers for Parsing Meta, Data & Type
*/
		public $link = null;
		public $link_arr = array();
		public $data = false;
		public $type = false;

		public $videos = null;
		public $episodes = null;
		public $reviews = null;
		public $recommendations = null;
		public $stats = null;
		public $characters_staff = null;
		public $pictures = null;
		public $more_info = null;

/*
	Identifiers for Parsing Algorithm
*/
		private $search = array();
		private $line = false;
		private $lineNo = false;
		private $matches = array();


/*
	Method: anime
	Parameter: Anime ID
	Returns: $this
*/
		public function anime($id = null) {
			if (is_null($this->link)) {
				$this->link = $this->types["anime"].$id;
				$this->type = "anime";
			}

			if ($this->is_link2($this->link)) {
				if ($this->link_exists($this->link)) {
					$this->link_arr = @file($this->link);
					array_walk($this->link_arr, array($this, 'trim'));
				} else {
					http_response_code(404);
					throw new \Exception("Could not access \"".$this->link."\"", 1);
					return false;
				}
			} else {
				if (file_exists($this->link)) {
					$this->link_arr = @file($this->link);
					array_walk($this->link_arr, array($this, 'trim'));
				} else {
					throw new \Exception("File not found \"".$this->link."\"", 1);
					return false;					
				}
			}

			if (!empty($this->data)) {
				unset($this->data);
				$this->data = array();
			}

			$this->setSearch("link-canonical", "/<link rel=\"canonical\" href=\"(.*)\" \/>/", function() {
				return $this->matches[1];
			});

			$this->setSearch("title", "#<h1 class=\"h1\"><span itemprop=\"name\">(.*)<\/span><\/h1>#", function() {
				return $this->matches[1];
			});

			$this->setSearch("title-english", '~<span class="dark_text">English:</span> (.*)~', function() {
				return $this->matches[1];
			});

			$this->setSearch("synonyms", "#<span class=\"dark_text\">Synonyms:<\/span> (.*)#", function() {
				return $this->matches[1];
			});

			$this->setSearch("japanese", "#<span class=\"dark_text\">Japanese:<\/span> (.*)#", function() {
				return $this->matches[1];
			});

			$this->setSearch("image", "#<img src=\"(.*)\" alt=\"(.*)\" class=\"ac\" itemprop=\"image\">#", function() {
				return $this->matches[1];
			});

			$this->setSearch("type", "#<span class=\"dark_text\">Type:<\/span>#", function() {
				$matches = array();
				preg_match('~<a href="(.*)">(.*?)</a></div>~', $this->link_arr[$this->lineNo + 1], $matches);
				return $matches[2];
			});

			$this->setSearch("episodes", "#<span class=\"dark_text\">Episodes:<\/span>#", function() {
				return (int) $this->link_arr[$this->lineNo + 1];
			});

			$this->setSearch("status", "#<span class=\"dark_text\">Status:<\/span>#", function() {
				return $this->link_arr[$this->lineNo + 1];
			});

			$this->setSearch("aired", "#<span class=\"dark_text\">Aired:<\/span>#", function() {
				return $this->link_arr[$this->lineNo + 1];
			});

			$this->setSearch("premiered", "#<span class=\"dark_text\">Premiered:<\/span>#", function() {
				$matches = array();
				if (preg_match("/<a href=\"(.*)\">(.*)<\/a>/", $this->link_arr[$this->lineNo + 1], $matches)) {
					return $matches[2];
				} else { return false; }
			});

			$this->setSearch("broadcast", "#<span class=\"dark_text\">Broadcast:<\/span>#", function() {
				return $this->link_arr[$this->lineNo + 1];
			});

			$this->setSearch("producer", "#<span class=\"dark_text\">Producers:<\/span>#", function() {
				$matches = array();
				$return = array();
				if (!preg_match("/None found/", $this->link_arr[$this->lineNo + 1])) {
					if (strpos($this->link_arr[$this->lineNo + 1], ",")) {
						$arr = explode("</a>,", $this->link_arr[$this->lineNo + 1]);
						foreach ($arr as $key => $value) {
							preg_match("#<a href=\"\/anime\/producer\/(.*)\" title=\"(.*)\">([\s\S]*)(</a>|)#", $value, $matches);
							$return[] = array($matches[1], $matches[3]);
						}
					} else {
						preg_match("#<a href=\"\/anime\/producer\/(.*)\" title=\"(.*)\">(.*)<\/a>#", $this->link_arr[$this->lineNo + 1], $matches);
						$return = array($matches[1], $matches[3]);
					}
				} else {
					$return = false;
				}

				return $return;
			});

			$this->setSearch("licensor", "#<span class=\"dark_text\">Licensors:<\/span>#", function() {
				$matches = array();
				$return = array();
				if (!preg_match("/None found/", $this->link_arr[$this->lineNo + 1])) {
					if (strpos($this->link_arr[$this->lineNo + 1], ",")) {
						$arr = explode("</a>,", $this->link_arr[$this->lineNo + 1]);
						foreach ($arr as $key => $value) {
							preg_match("#<a href=\"\/anime\/producer\/(.*)\" title=\"(.*)\">(.*)(<\/a>|)#", $value, $matches);
							$matches[3] = trim(strip_tags($matches[3]));
							$return[] = array($matches[1], $matches[3]);
						}
					} else {
						preg_match("#<a href=\"\/anime\/producer\/(.*)\" title=\"(.*)\">(.*?)<\/a>#", $this->link_arr[$this->lineNo + 1], $matches);
						$return = array($matches[1], $matches[3]);
					}
				} else {
					$return = false;
				}

				return $return;	
			});

			$this->setSearch("studio", "#<span class=\"dark_text\">Studios:<\/span>#", function() {
				$matches = array();
				$return = array();
				if (!preg_match("/None found/", $this->link_arr[$this->lineNo + 1])) {
					if (strpos($this->link_arr[$this->lineNo + 1], ",")) {
						$arr = explode("</a>,", $this->link_arr[$this->lineNo + 1]);
						foreach ($arr as $key => $value) {
							preg_match("#<a href=\"\/anime\/producer\/(.*)\" title=\"(.*)\">(.*)(<\/a>|)#", $value, $matches);
							$return[] = array($matches[1], $matches[3]);
						}
					} else {
						preg_match("#<a href=\"\/anime\/producer\/(.*)\" title=\"(.*)\">(.*)<\/a>#", $this->link_arr[$this->lineNo + 1], $matches);
						$return = array($matches[1], $matches[3]);
					}
				} else {
					$return = false;
				}

				return $return;	
			});

			$this->setSearch("source", "#<span class=\"dark_text\">Source:<\/span>#", function() {
				return $this->link_arr[$this->lineNo + 1];
			});

			$this->setSearch("genre", "#<span class=\"dark_text\">Genres:<\/span>#", function() {
				if (preg_match('~No genres have been added yet~', $this->link_arr[$this->lineNo + 1])) {
					return array();
				}
				$return = array();
				$matches = array();
				if (strpos($this->link_arr[$this->lineNo + 1], ",")) {
					$arr = explode(",", $this->link_arr[$this->lineNo + 1]);
					foreach ($arr as $key => $value) {
						preg_match("#<a href=\"\/anime\/genre\/(.*)\" title=\"(.*)\">(.*)<\/a>#", $value, $matches);
						$return[] = array($matches[1], $matches[3]);
					}
				} else {
					preg_match("#<a href=\"\/anime\/genre\/(.*)\" title=\"(.*)\">(.*)<\/a>#", $this->link_arr[$this->lineNo + 1], $matches);
					$return = array($matches[1], $matches[3]);
				}

				return $return;
			});

			$this->setSearch("duration", "#<span class=\"dark_text\">Duration:<\/span>#", function() {
				return $this->link_arr[$this->lineNo + 1];
			});

			$this->setSearch("rating", "#<span class=\"dark_text\">Rating:<\/span>#", function() {
				return $this->link_arr[$this->lineNo + 1];
			});

			$this->setSearch("score", "#<span class=\"dark_text\">Score:<\/span>#", function() {
				$matches = array();
				preg_match('~<span(.*?)>(.*)</span><sup>1</sup> \(scored by <span(.*?)>(.*)</span> users\)~', $this->link_arr[$this->lineNo + 1], $matches);
				return array((float) $matches[2], (int) str_replace(",", "", $matches[4]));
			});

			$this->setSearch("ranked", "#<span class=\"dark_text\">Ranked:<\/span>#", function() {
				$matches = array();
				if (!preg_match("/N\/A<sup>2<\/sup>/", $this->link_arr[$this->lineNo + 1], $matches)) {
					preg_match("/#(.*)<sup>2<\/sup>/", $this->link_arr[$this->lineNo + 1], $matches);
					return (int) $matches[1];
				} else {
					return -1;
				}
			});

			$this->setSearch("popularity", "#<span class=\"dark_text\">Popularity:<\/span>#", function() {
				$matches = array();
				preg_match("/#(.*)/", $this->link_arr[$this->lineNo + 1], $matches);
				return (int) $matches[1];				
			});

			$this->setSearch("members", "#<span class=\"dark_text\">Members:<\/span>#", function() {
				return(int) str_replace(",", "", $this->link_arr[$this->lineNo + 1]);
			});

			$this->setSearch("favorites", "#<span class=\"dark_text\">Favorites:<\/span>#", function() {
				return (int) str_replace(",", "", $this->link_arr[$this->lineNo + 1]);
			});

			$this->setSearch("synopsis", "%<meta property=\"og:description\" content=\"(.*)\">%", function() {
				return strip_tags($this->matches[1]);
			});

			$this->setSearch("related", "~<table class=\"anime_detail_related_anime\"~", function() {
				$return = array();
				$matches = array();
				// i'm sure there's a better way around this... x.x

				$workingLine = $this->link_arr[$this->lineNo];
				$workingLine = substr($workingLine, strpos($workingLine, "<table class=\"anime_detail_related_anime\""));
				$workingLine = substr($workingLine, strpos($workingLine, "<tr>") + 4);
				$workingLine = substr($workingLine, 0, strpos($workingLine, "</table>"));

				$workingLine = str_replace("</td>", "</td>,,,,", $workingLine);
				$workingLine = explode(",,,,", $workingLine);

				$title = "";
				foreach ($workingLine as $key => $value) {
					if (!empty($value)) {
						$tmp = null;
						preg_match("~<td.*?>(.*?)</td>~", $value, $tmp);
						$working = $tmp[1];
						if (preg_match("~<a href=\"(.*)\">(.*)</a>~", $working)) {
							$working2 = explode("</a>,", $working);
							if (count($working2) > 1) {
								foreach ($working2 as $key2 => $value2) {
									$tmp2 = null;
									preg_match("~<a href=\"(.*)\">(.*)(</a>|)~", $value2, $tmp2);
									$return[$title][] = array($tmp2[2], $tmp2[1]);									
								}
							} else {
								preg_match("~<a href=\"(.*)\">(.*)(</a>|)~", $working, $tmp);
								$return[$title] = array($tmp[2], $tmp[1]);
							}
						} else {
							$title = str_replace(":", "", $working);
						}
					}
				}


				return $return;
			});


			$this->setSearch("background", '~</div>Background</h2>~', function() {
				$matches = array();
				if (!preg_match('~No background information has been added to this title.~', $this->line)) {

					if (preg_match('~</div>Background</h2>([\s\S]*)<div class="border_top~', $this->line, $matches)) {
						return strip_tags($matches[1]);
					} else {
						preg_match('~</div>Background</h2>([\s\S]*)~', $this->line, $matches);
						$running = true;
						$string = $matches[1];
						$i = 1;
						while ($running) {
							if (preg_match('~<div class="border_top"~', $this->link_arr[$this->lineNo + $i])) {
								$running = false;
							}
							$string .= $this->link_arr[$this->lineNo + $i];
							$i++;
						}
						$string = substr($string, 0, strpos($string, '<div class="border_top'));
						return strip_tags($string);
					}
					
				} else {
					return "";
				}
			});


			$this->setSearch("opening-theme", '~<div class="theme-songs js-theme-songs opnening">([\s\S]*)</div>~', function() {
				$themes = explode('<span class="theme-song">', $this->matches[1]);
				foreach ($themes as $key => &$value) {
					$value = substr($value, 0, -11);
				}
				array_shift($themes);
				return $themes;
			});

			$this->setSearch("ending-theme", '~<div class="theme-songs js-theme-songs ending">([\s\S]*)</div>~', function() {
				$themes = explode('<span class="theme-song">', $this->matches[1]);
				foreach ($themes as $key => &$value) {
					$value = substr($value, 0, -11);
				}
				array_shift($themes);
				return $themes;
			});

			$this->data = array();

			foreach ($this->link_arr as $lineNo => $line) {
				$this->line = $line;
				$this->lineNo = $lineNo;

				$this->find();
			}

			unset($this->matches);
			$this->matches = array();

			$this->data = (empty($this->data)) ? false : $this->data;

			return $this;
		}


/*
	Method: manga
	Parameter: Manga ID
	Returns: $this
*/
		public function manga($id = null) {

			if (is_null($this->link)) {
				$this->link = $this->types["manga"].$id;
				$this->type = "manga";
			}

			if ($this->is_link2($this->link)) {
				if ($this->link_exists($this->link)) {
					$this->link_arr = @file($this->link);
					array_walk($this->link_arr, array($this, 'trim'));
				} else {
					http_response_code(404);
					throw new \Exception("Could not access \"".$this->link."\"", 1);
					return false;
				}
			} else {
				if (file_exists($this->link)) {
					$this->link_arr = @file($this->link);
					array_walk($this->link_arr, array($this, 'trim'));
				} else {
					throw new \Exception("File not found \"".$this->link."\"", 1);
					return false;					
				}
			}

			if (!empty($this->data)) {
				unset($this->data);
				$this->data = array();
			}

			$this->setSearch("link-canonical", "/<link rel=\"canonical\" href=\"(.*)\" \/>/", function() {
				return $this->matches[1];
			});

			$this->setSearch("title", "#<h1 class=\"h1\"><span itemprop=\"name\">(.*)<\/span><\/h1>#", function() {
				return $this->matches[1];
			});

			$this->setSearch("title-english", '~<span class="dark_text">English:</span> (.*?)</div>~', function() {
				return $this->matches[1];
			});

			$this->setSearch("status", '~<div class="spaceit"><span class="dark_text">Status:</span> (.*)</div>~', function() {
				return $this->matches[1];
			});

			$this->setSearch("synonyms", '#<span class=\"dark_text\">Synonyms:<\/span> (.*)</div><div class=\"spaceit_pad\"><span class=\"dark_text\">Japanese:</span>#', function() {
				return $this->matches[1];
			});

			$this->setSearch("japanese", "#<span class=\"dark_text\">Japanese:<\/span>(.*?)<\/div>#", function() {
				return trim($this->matches[1]);
			});

			$this->setSearch("image", "#<img src=\"(.*)\" alt=\"(.*)\" itemprop=\"image\" class=\"ac\">#", function() {
				return $this->matches[1];
			});

			$this->setSearch("type", '~<span class="dark_text">Type:</span> <a href="(.*)">(.*?)</a></div>~', function() {
				return $this->matches[2];
			});

			$this->setSearch("volumes", "#<span class=\"dark_text\">Volumes:<\/span> (.*)$#", function() {
				return ($this->matches[1] != 'Unknown') ? (int) trim($this->matches[1]) : $this->matches[1];
			});

			$this->setSearch("chapters", "#<span class=\"dark_text\">Chapters:<\/span> (.*)$#", function() {
				return ($this->matches[1] != 'Unknown') ? (int) trim($this->matches[1]) : $this->matches[1];
			});

			$this->setSearch("published", "#<span class=\"dark_text\">Published:<\/span>(.*)<\/div>#", function() {
				return trim($this->matches[1]);
			});

			$this->setSearch("genre", "#<span class=\"dark_text\">Genres:<\/span>#", function() {
				$return = array();
				$matches = array();

				if (!preg_match('`No genres have been added yet.`', $this->link_arr[$this->lineNo + 1])) {
					if (strpos($this->link_arr[$this->lineNo + 1], ",")) {
						$arr = explode(",", $this->link_arr[$this->lineNo + 1]);
						foreach ($arr as $key => $value) {
							preg_match("#<a href=\"\/manga\/genre\/(.*)\" title=\"(.*)\">(.*)<\/a>#", $value, $matches);
							$return[] = array($matches[1], $matches[3]);
						}
					} else {
						preg_match("#<a href=\"\/manga\/genre\/(.*)\" title=\"(.*)\">(.*)<\/a>#", $this->link_arr[$this->lineNo + 1], $matches);
						$return = array(
							'name' => $matches[3], 
							'url' => $matches[1]
							);
					}
				}

				return $return;
			});

			$this->setSearch("author", "#<span class=\"dark_text\">Authors:<\/span>#", function() {
				$return = array();

				if (!preg_match("/None/", $this->link_arr[$this->lineNo + 1])) {
					$_authors = array();
					preg_match('`^(.*?)</div>`', trim($this->link_arr[$this->lineNo + 1]), $_authors);

					$authors = explode("),", $_authors[1]);

					foreach ($authors as $key => $value) {
						if (!strpos($value, ')')) {$value .= ')'; }
						$break = array();
						preg_match('`<a href=\"(.*)\">(.*)</a>`', $value, $break);
						$return[] = array(
							'name' => $break[2],
							'url' => $break[1]
						);
					}
				}

				return $return;
			});

			$this->setSearch("serialization", "#<span class=\"dark_text\">Serialization:<\/span>#", function() {
				$return = array();
				$matches = array();
				if (!preg_match("/None/", $this->link_arr[$this->lineNo + 1])) {
					if (strpos($this->link_arr[$this->lineNo + 1], ">,")) {
						$arr = explode("</a>,", $this->link_arr[$this->lineNo + 1]);
						foreach ($arr as $key => $value) {
							preg_match("#<a href=\"(.*)\">(.*)(<\/a>|)#", $value, $matches);
							$return[] = array($matches[1], $matches[2]);
						}
					} else {
						preg_match('/^<a.*?href=(["\'])(.*?)\1.*>(.*)<\/a>/', $this->link_arr[$this->lineNo + 1], $matches);
						$return = array($matches[3], $matches[2]);
					}
				}
				return $return;

			});


			$this->setSearch("ranked", "~<span class=\"dark_text\">Ranked:<\/span> #(.*[[:alnum:]])<sup>~", function() {
				return ($this->matches[1] == "N/A" ? -1 : (int) $this->matches[1]);
			});

			$this->setSearch("score", '~<span class="dark_text">Score:</span> <span itemprop="ratingValue">(.*)</span><sup><small>1</small></sup> <small>\(scored by <span itemprop="ratingCount">(.*)</span> users\)</small>~', function() {
				return array((float) $this->matches[1], (int) str_replace(",", "", $this->matches[2]));
			});

			$this->setSearch("popularity", "~<span class=\"dark_text\">Popularity:<\/span> #(.*[[:alnum:]])<\/div>~", function() {
				return ($this->matches[1] == "N/A" ? -1 : (int) $this->matches[1]);
			});

			$this->setSearch("members", "#<span class=\"dark_text\">Members:<\/span>(.*)<\/div>#", function() {
				$this->matches[1] = str_replace(",", "", trim($this->matches[1]));
				return (int) $this->matches[1];
			});

			$this->setSearch("favorites", '~<div><span class="dark_text">Favorites:</span> (.*)</div>~', function() {
				return (int) str_replace(",", "", trim($this->matches[1]));
			});

			$this->setSearch("synopsis", "%<meta property=\"og:description\" content=\"(.*)\">%", function() {
				return strip_tags($this->matches[1]);
			});


			$this->setSearch("related", "~<table class=\"anime_detail_related_anime\"~", function() {
				$return = array();
				$matches = array();
				// i'm sure there's a better way around this... x.x

				$workingLine = $this->link_arr[$this->lineNo];
				$workingLine = substr($workingLine, strpos($workingLine, "<table class=\"anime_detail_related_anime\""));
				$workingLine = substr($workingLine, strpos($workingLine, "<tr>") + 4);
				$workingLine = substr($workingLine, 0, strpos($workingLine, "</table>"));

				$workingLine = preg_replace('~<td.*?>~', '', $workingLine);
				$workingLine = str_replace("<tr>", '', $workingLine);
				$workingLine = explode("</td>", $workingLine);

				$arr = array();
				$workingLineCount = count($workingLine);
				for ($i = 0; $i <= $workingLineCount; $i++) {
					if ($i % 2 == 0) {
						if (!empty($workingLine[$i])) {
							$title = $workingLine[$i];
							$arr = explode("</a>,", $workingLine[$i + 1]);
							$newArr = array();
							foreach ($arr as $key => $value) {
								$filtered = array();
								preg_match("~<a href=\"(.*)\">(.*)(</a>|)~", $value, $filtered);
								$newArr[] = array(
									'name' => $filtered[2],
									'url' => $filtered[1]
								);
							}
							$return[$title] = $newArr;
						}
					}
				}

				return $return;
			});

			$this->setSearch("background", '~</div>Background</h2>~', function() {
				$matches = array();

				if (!preg_match('~No background information has been added to this title.~', $this->line)) {

					if (preg_match('~</div>Background</h2>([\s\S]*)<div class="border_top~', $this->line, $matches)) {
						return strip_tags($matches[1]);
					} else {
						preg_match('~</div>Background</h2>([\s\S]*)~', $this->line, $matches);
						$running = true;
						$string = $matches[1];
						$i = 1;
						while ($running) {
							if (preg_match('~<div class="border_top"~', $this->link_arr[$this->lineNo + $i])) {
								$running = false;
							}
							$string .= $this->link_arr[$this->lineNo + $i];
							$i++;
						}
						$string = substr($string, 0, strpos($string, '<div class="border_top'));
						return strip_tags($string);
					}
				} else {
					return "";
				}
				
			});


			$this->data = array();

			foreach ($this->link_arr as $lineNo => $line) {
				$this->line = $line;
				$this->lineNo = $lineNo;
				$this->find();
			}

			unset($this->matches);
			$this->matches = array();

			$this->data = (empty($this->data)) ? false : $this->data;

			return $this;		
		}


/*
	Method: character
	Parameter: Character ID
	Returns: $this
*/
		public function character($id) {
			$this->link = $this->types["character"].$id;
			$this->type = "character";

			if ($this->link_exists($this->link)) {
				$this->link_arr = @file($this->link);
				array_walk($this->link_arr, array($this, 'trim'));
			} else {
				http_response_code(404);
				throw new \Exception("Could not access \"".$this->link."\"", 1);
				return false;
			}

			if (!empty($this->data)) {
				unset($this->data);
				$this->data = array();
			}

			$this->setSearch("name", "~<div class=\"normal_header\" style=\"height: 15px;\">(.*) <span style=\"font-weight: normal;\"><small>(.*)</small></span></div>~", function() {
				return $this->matches[1];
			});

			$this->setSearch("name-japanese", "~<div class=\"normal_header\" style=\"height: 15px;\">(.*) <span style=\"font-weight: normal;\"><small>(.*)</small></span></div>~", function() {
				return $this->matches[2];
			});


			$this->setSearch("about", "~<div class=\"normal_header\" style=\"height: 15px;\">(.*) <span style=\"font-weight: normal;\"><small>(.*)</small></span></div>([\s\S]*)~", function() {
				$match = array();
				$match[] = $this->matches[3];

				$finding = true;
				$i = $this->lineNo;
				while ($finding) {
					if (preg_match('~<div class="normal_header">Voice Actors</div>~', $this->link_arr[$i])) {
						$finding = false;
					} else {
						$i++;
						$match[] = $this->link_arr[$i];
					}
				}

				//filter out the residue
				$about = implode(" ", $match);
				$about = str_replace("<br />", "\n", $about);
				$about = str_replace("<div class=\"normal_header\">Voice Actors</div>", "", $about);
				$about = str_replace("<div class=\"spoiler\"><input type=\"button\" class=\"button show_button\" onClick=\"this.nextSibling.style.display='inline-block';this.style.display='none';\" data-showname=\"Show spoiler\" data-hidename=\"Hide spoiler\" value=\"Show spoiler\"><span class=\"spoiler_content\" style=\"display:none\"><input type=\"button\" class=\"button hide_button\" onClick=\"this.parentNode.style.display='none';this.parentNode.parentNode.childNodes[0].style.display='inline-block';\" value=\"Hide spoiler\">", "", $about);
				$about = str_replace("<br>", "", $about);
				$about = str_replace("</span></div>", "", $about);
				$about = htmlspecialchars_decode($about);
				return $about;
			});

			$this->setSearch("animeography", "~<div class=\"normal_header\">Animeography</div>~", function() {
				$running = true;
				$i = 1;
				$animeography = array();
				while ($running) {
					$line = $this->link_arr[$this->lineNo + $i];
					if (preg_match("~</table>~", $line)) {
						$running = false;
					}

					if (preg_match("~<tr>~", $line)) {
						$i++;
						$animeMeta = array();
						preg_match('~<td width="25" class="borderClass" valign="top"><div class="picSurround"><a href="(.*)"><img src="(.*)" border="0"></a></div></td>~', $this->link_arr[$this->lineNo + $i], $animeMeta);
						$i++;
						$animeName = array();
						preg_match('~<td valign="top" class="borderClass"><a href="(.*)">(.*)</a>~', $this->link_arr[$this->lineNo + $i], $animeName);
						$animeography[] = array(
							'name' => $animeName[2],
							'link' => $animeMeta[1],
							'image' => $animeMeta[2]
						);
					}
					$i++;
				}
				return $animeography;
			});

			$this->setSearch("mangaography", "~<div class=\"normal_header\">Mangaography</div>~", function() {
				$running = true;
				$i = 1;
				$mangaography = array();
				while ($running) {
					$line = $this->link_arr[$this->lineNo + $i];
					if (preg_match("~</table>~", $line)) {
						$running = false;
					}

					if (preg_match("~<tr>~", $line)) {
						$i++;
						$mangaMeta = array();
						preg_match('~<td width="25" class="borderClass" valign="top"><div class="picSurround"><a href="(.*)"><img src="(.*)" border="0"></a></div></td>~', $this->link_arr[$this->lineNo + $i], $mangaMeta);
						$i++;
						$mangaName = array();
						preg_match('~<td valign="top" class="borderClass"><a href="(.*)">(.*)</a>~', $this->link_arr[$this->lineNo + $i], $mangaName);
						$mangaography[] = array(
							'name' => $mangaName[2],
							'link' => $mangaMeta[1],
							'image' => $mangaMeta[2]
						);
					}
					$i++;
				}
				return $mangaography;
			});

			$this->setSearch("voice-actors", "~<div class=\"normal_header\">Voice Actors</div>~", function() {
				$running = true;
				$i = 1;
				$voiceActors = array();
				while ($running) {
					$line = $this->link_arr[$this->lineNo + $i]; // bugs
					if (
						preg_match('~<h2><div class="floatRightHeader">~', $line) ||
						preg_match('~<div class="mauto clearfix pt24" style="width:760px;">~', $line)
						) {
						$running = false;
					}

					if (preg_match("~<tr>~", $line)) {
						$i++;
						$personMeta = array();
						preg_match('~<td class="borderClass" valign="top" width="25"><div class="picSurround"><a href="(.*)"><img src="(.*)" border="0"></a></div></td>~', $this->link_arr[$this->lineNo + $i], $personMeta);
						$i++;
						$personName = array();
						preg_match('~<td class="borderClass" valign="top"><a href="(.*)">(.*)</a>~', $this->link_arr[$this->lineNo + $i], $personName);
						$i++;
						$personType = array();
						preg_match('~<div style="margin-top: 2px;"><small>(.*)</small></div>~', $this->link_arr[$this->lineNo + $i], $personType);
						$voiceActors[] = array(
							'name' => $personName[2],
							'link' => $personMeta[1],
							'image' => $personMeta[2],
							'language' => $personType[1]
						);
					}
					$i++;
				}

				return $voiceActors;
			});

			$this->setSearch("member-favorites", "~Member Favorites: (.*)~", function() {
				return (int) str_replace(',', '', trim($this->matches[1]));
			});

			$this->setSearch('image', '~<td width="225" class="borderClass" style="border-width: 0 1px 0 0;" valign="top"><div style="text-align: center;">(<a href="(.*)"><img src="(.*)" alt="(.*)"></a>|<a href="(.*)" class="btn-detail-add-picture"><i class="fa fa-plus-circle fs18 icon-plus-circle"></i><i class="fa fa-camera fs48"></i><br><span class="text">Add Picture</span></a></a>)</div>~', function() {
				return $this->matches[3];
			});

			$this->data = array();

			foreach ($this->link_arr as $lineNo => $line) {
				$this->line = $line;
				$this->lineNo = $lineNo;
				
				$this->find();
			}

			unset($this->matches);
			$this->matches = array();

			$this->data = (empty($this->data)) ? false : $this->data;

			return $this;
		}


/*
	Method: person
	Parameter: Person ID
	Returns: $this
*/
		public function person($id) {
			$this->link = $this->types["people"].$id;
			$this->type = "person";

			if ($this->link_exists($this->link)) {
				$this->link_arr = @file($this->link);
				array_walk($this->link_arr, array($this, 'trim'));
			} else {
				http_response_code(404);
				throw new \Exception("Could not access \"".$this->link."\"", 1);
				return false;
			}

			if (!empty($this->data)) {
				unset($this->data);
				$this->data = array();
			}

			$this->setSearch('link-canonical', '~<link rel="canonical" href="(.*)" />~', function() {
				return $this->matches[1];
			});

			$this->setSearch('given-name', '~<div class="spaceit_pad"><span class="dark_text">Given name:</span> (.*)</div>~', function() {
				return $this->matches[1];
			});

			$this->setSearch('family-name', '~<span class="dark_text">Family name:</span> (.*)<div class="spaceit_pad"><span class="dark_text">Alternate names:</span>~', function() {
				return $this->matches[1];
			});

			$this->setSearch('alternate-names', '~<span class="dark_text">Alternate names:</span> (.*)</div><div class="spaceit_pad">~', function() {
				$alternateNames = $this->matches[1];
				if (strpos($alternateNames, ",")) {
					$_alternativeNames = explode(",", $alternateNames);
					foreach ($_alternativeNames as $key => &$value) {
						$value = trim($value);
					}
				} else {
					return $alternateNames;
				}
			});

			$this->setSearch('birthday', '~<span class="dark_text">Birthday:</span> (.*)</div><span class="dark_text">Website:</span>~', function() {
				return $this->matches[1];
			});

			$this->setSearch('website', '~<span class="dark_text">Website:</span> <a href="(.*)">(.*)</a>~', function() {
				return $this->matches[1];
			});

			$this->setSearch('member-favorites', '~<div class="spaceit_pad"><span class="dark_text">Member Favorites:</span> (.*)</div>~', function() {
				return (int) str_replace(',', '', $this->matches[1]);
			});

			$this->setSearch('more', '~<div class="people-informantion-more js-people-informantion-more">([\s\S]*)</div>~', function() {
				return $this->matches[1];
			});

			$this->setSearch('voice-acting-role', '~</div>Voice Acting Roles</div>~', function() {
				$running = true;
				$i = 1;
				$voiceActingRoles = array();
				while ($running) {
					$line = $this->link_arr[$this->lineNo + $i];
					if (preg_match('~</span>Anime Staff Positions</div>~', $line)) {
						$running = false;
					}

					if (preg_match("~<tr>~", $line)) {
						$i++;
						$animeMeta = array();
						preg_match('~<td valign="top" class="borderClass" width="25"><div class="picSurround"><a href="(.*)"><img data-src="(.*)" border="0" width="23" class="lazyload"></a></div></td>~', $this->link_arr[$this->lineNo + $i], $animeMeta);
						$i++;
						$animeName = array();
						preg_match('~<td valign="top" class="borderClass"><a href="(.*)">(.*)</a><div class="spaceit_pad">~', $this->link_arr[$this->lineNo + $i], $animeName);
						$i += 2;
						$char = array();
						preg_match('~<td valign="top" class="borderClass" align="right" nowrap><a href="(.*)">(.*)</a>&nbsp;<div class="spaceit_pad">(.*)&nbsp;</div></td>~', $this->link_arr[$this->lineNo + $i], $char);
						$i++;
						$charMeta = array();
						preg_match('~<td valign="top" class="borderClass" width="25"><div class="picSurround"><a href="(.*)"><img data-src="(.*)" border="0" alt="(.*)" width="23" class="lazyload"></a></div></td>~', $this->link_arr[$this->lineNo + $i], $charMeta);
						$voiceActingRoles[] = array(
							'anime' => array(
								'name' => $animeName[2],
								'link' => $animeMeta[1],
								'image' => $animeMeta[2]
							),
							'character' => array(
								'name' => $char[2],
								'link' => $charMeta[1],
								'image' => $charMeta[2],
								'role' => $char[3]
							),
						);
					}
					$i++;
				}
				return $voiceActingRoles;
			});

			$this->setSearch('anime-staff-position', '~</span>Anime Staff Positions</div>~', function() {
				$running = true;
				$i = 1;
				$animeStaffPositions = array();
				while ($running) {
					$line = $this->link_arr[$this->lineNo + $i];
					if (preg_match('~</span>Published Manga</div>~', $line)) {
						$running = false;
					}

					if (preg_match("~<tr>~", $line)) {
						$i++;
						$animeMeta = array();
						preg_match('~<td valign="top" class="borderClass" width="25"><div class="picSurround"><a href="(.*)"><img data-src="(.*)" border="0" width="23" class="lazyload"></a></div></td>~', $this->link_arr[$this->lineNo + $i], $animeMeta);
						$i++;
						$animeName = array();
						preg_match('~<td valign="top" class="borderClass"><a href="(.*)">(.*)</a><div class="spaceit_pad">~', $this->link_arr[$this->lineNo + $i], $animeName);
						$i++;
						$role = array();
						preg_match('~<a href="(.*)" title="Quick add anime to my list" class="button_add">add</a> <small>(.*)</small>~', $this->link_arr[$this->lineNo + $i], $role);
						$animeStaffPositions[] = array(
							'anime' => array(
								'name' => $animeName[2],
								'link' => $animeMeta[1],
								'image' => $animeMeta[2]
							),
							'role' => $role[2]
						);
					}
					$i++;
				}
				return $animeStaffPositions;
			});

			$this->setSearch('published-manga', '~</span>Published Manga</div>~', function() {
				$running = true;
				$i = 1;
				$publsihedManga = array();
				while ($running) {
					$line = $this->link_arr[$this->lineNo + $i];
					if (preg_match('~</table>~', $line)) {
						$running = false;
					}

					if (preg_match("~<tr>~", $line)) {
						$i++;
						$mangaMeta = array();
						preg_match('~<td valign="top" class="borderClass" width="25"><div class="picSurround"><a href="(.*)"><img data-src="(.*)" border="0" width="23" class="lazyload"></a></div></td>~', $this->link_arr[$this->lineNo + $i], $mangaMeta);
						$i++;
						$mangaName = array();
						preg_match('~<td valign="top" class="borderClass"><a href="(.*)">(.*)</a><div class="spaceit_pad">~', $this->link_arr[$this->lineNo + $i], $mangaName);
						$i++;
						$role = array();
						preg_match('~<a href="(.*)" title="Quick add manga to my list" class="button_add">add</a> <small>(.*)</small>~', $this->link_arr[$this->lineNo + $i], $role);
						$publsihedManga[] = array(
							'manga' => array(
								'name' => $mangaName[2],
								'link' => $mangaMeta[1],
								'image' => $mangaMeta[2]
							),
							'role' => $role[2]
						);
					}
					$i++;
				}
				return $publsihedManga;
			});

			$this->setSearch('image', '~<td width="225" class="borderClass" style="border-width: 0 1px 0 0;" valign="top"><div style="text-align: center; style="margin-bottom: 3px;">(<a href="(.*)"><img src="(.*)" alt="(.*)"></a>|<a href="(.*)" class="btn-detail-add-picture"><i class="fa fa-plus-circle fs18 icon-plus-circle"></i><i class="fa fa-camera fs48"></i><br><span class="text">Add Picture</span></a></a>)</div>~', function() {
				return $this->matches[3];
			});

			$this->data = array();

			foreach ($this->link_arr as $lineNo => $line) {
				$this->line = $line;
				$this->lineNo = $lineNo;
				
				$this->find();
			}

			unset($this->matches);
			$this->matches = array();

			$this->data = (empty($this->data)) ? false : $this->data;

			return $this;
		}


/*
	Method: search
	Parameters: $query <string>, $page <integer>
	Return: boolean
*/
	public function search($type, $query, $page = 1) {

	}

/*
	Method:
*/
	public function user_list($username, $type) {
		if (!$type == 'anime' || !$type == 'manga') {
			throw new \Exception("Invalid type request (anime|manga)", 1);
			return false;
		}

		if (!empty($this->data)) {
			$this->data = array();
		}

		$this->link = $this->types["list"]."?u=".$username."&status=all&type=".$type;

		$xml = simplexml_load_string(file_get_contents($this->link));
		$json = json_encode($xml);
		$this->data = json_decode($json, true);
	}

/*
	Extended Chaining Methods
	These are to be called/chained after the anime/manga/character/person method
	Not all of these are supported by the parent methods
		For example, the Characters Page doesn't have any 'videos' or 'episodes' so it can't return that data
*/

	/*
		Child Method: videos
		Parameter: None
		Returns: $this
		Parent Methods: anime
	*/
		public function videos() {}
	/*
		Child Method: episodes
		Parameter: None
		Returns: $this
		Parent Methods: anime
	*/
		public function episodes() {
			if (!isset($this->data['link-canonical'])) {
				throw new \Exception("This is a child request, make a parent request first!", 1);
				return false;
			}

			if (!$this->type == "anime") {
				throw new \Exception("This child method is only applicable to anime", 1);
				return false;
			}

			if (is_null($this->episodes)) {
				$this->episodes = $this->data['link-canonical']."/episode";
			}


			$this->unsetSearch();
			$this->data['episode'] = array();

			while (!is_null($this->episodes)) {
				$this->episodesExtract();
			}


			return $this;
		}

		private function episodesExtract() {
			$this->link_arr = array();

			if ($this->is_link2($this->episodes)) {
				if ($this->link_exists($this->episodes)) {
					$this->link_arr = @file($this->episodes);
					array_walk($this->link_arr, array($this, 'trim'));
				} else {
					http_response_code(404);
					throw new \Exception("Could not access \"".$this->episodes."\"", 1);
					return false;
				}
			} else {
				if (file_exists($this->episodes)) {
					$this->link_arr = @file($this->episodes);
					array_walk($this->link_arr, array($this, 'trim'));
				} else {
					throw new \Exception("File not found \"".$this->episodes."\"", 1);
					return false;					
				}
			}

			$ep = array();
			$this->episodes = null;
			foreach ($this->link_arr as $lineNo => $line) {
				if (preg_match('~<link rel="next" href="(.*)" />~', $line, $ep)) {
					$this->episodes = $ep[1];
					break;
				}
			}

			$this->setSearch('episode', '~<table border="0" cellspacing="0" cellpadding="0" width="100%" class="mt8 episode_list js-watch-episode-list ascend">~', function() {
				$running = true;
				$i = 1;
				$episode = array();
				while ($running) {
					$line = $this->link_arr[$this->lineNo + $i];
					if (preg_match("~</table>~", $line)) {
						$running = false;
					}

					if (preg_match('~<tr class="episode-list-data">~', $line)) {
						$i++;
						$_episodeNo = array();
						preg_match('~<td class="episode-number nowrap">(.*)</td>~', $this->link_arr[$this->lineNo + $i], $_episodeNo);
						$i++;
						$_episodeVideo = array();
						preg_match('~<td class="episode-video nowrap"><a href="(.*)"><img src="(.*)" width="20" height="19" alt="(.*)"></a></td>~', $this->link_arr[$this->lineNo + $i], $_episodeVideo);
						$i++;
						$_episodeTitle = array();
						preg_match('~<td class="episode-title">(<span class="fl-r di-ib pr4 icon-episode-type-bg">Filler</span>|<span class="fl-r di-ib pr4 icon-episode-type-bg">Recap</span>|)<a href="(.*)" class="fl-l fw-b ">(.*)</a>~', $this->link_arr[$this->lineNo + $i], $_episodeTitle);
						$filler = false;
						$recap = false;
						if (preg_match('~<span class="fl-r di-ib pr4 icon-episode-type-bg">Filler</span>~', $this->link_arr[$this->lineNo + $i])) {$filler = true; }
						if (preg_match('~<span class="fl-r di-ib pr4 icon-episode-type-bg">Recap</span>~', $this->link_arr[$this->lineNo + $i])) {$recap = true; }
						$i++;
						$_episodeTitleAlt = array();
						preg_match('~<br><span class="di-ib">(.*)&nbsp;(.*)</span>~', $this->link_arr[$this->lineNo + $i], $_episodeTitleAlt);
						$i += 2;
						$_episodeAired = array();
						preg_match('~<td class="episode-aired nowrap">(.*)</td>~', $this->link_arr[$this->lineNo + $i], $_episodeAired);
						$i += 2;
						$_episodeForum = array();
						preg_match('~<a href="(.*)"><i class="fa fa-comments mr4"></i>Forum</a></td>~', $this->link_arr[$this->lineNo + $i], $_episodeForum);

						$titleJapanese = (!empty($_episodeTitleAlt)) ? $_episodeTitleAlt[1] : "";
						$titleRomanji = (!empty($_episodeTitleAlt)) ? $_episodeTitleAlt[2] : "";
						$video_url = (!empty($_episodeVideo)) ? $_episodeVideo[1] : "";
						$aired = ($_episodeAired[1] != 'N/A') ? $_episodeAired[1] : "";
						$forum_url = (!empty($_episodeForum[1])) ? $_episodeForum[1] : "";

						$episode[] = array(
							'id' => (int) $_episodeNo[1],
							'title' => $_episodeTitle[2],
							'title-japanese' => $titleJapanese,
							'title-romanji' => $titleRomanji,
							'aired' => $aired,
							'filler' => $filler,
							'recap' => $recap,
							'video_url' => $video_url,
							'forum_url' => $forum_url
						);
					}
					$i++;
				}

				return $episode;
			}, null, true);



			foreach ($this->link_arr as $lineNo => $line) {
				$this->line = $line;
				$this->lineNo = $lineNo;
				
				$this->find();
			}

			unset($this->matches);
			$this->matches = array();
		}

	/*
		Child Method: reviews
		Parameter: None
		Returns: $this
		Parent Methods: anime, manga
	*/
		public function reviews() {}

	/*
		Child Method: recommendations
		Parameter: None
		Returns: $this
		Parent Methods: anime, manga
	*/
		public function recommendations() {}

	/*
		Child Method: stats
		Parameter: None
		Returns: $this
		Parent Methods: anime, manga
	*/
		public function stats() {}

	/*
		Child Method: characters_staff
		Parameter: None
		Returns: $this
		Parent Methods: anime, manga
	*/
		public function characters_staff() {
			if (!isset($this->data['link-canonical'])) {
				throw new \Exception("This is a child request, make a parent request first!", 1);
				return false;
			}

			if (!$this->type == "anime" || !$this->type == "manga") {
				throw new \Exception("This child method is only applicable to anime/manga", 1);
				return false;
			}

			if (is_null($this->characters_staff)) {
				$this->characters_staff = $this->data['link-canonical']."/characters";
			}

			$this->link_arr = array();

			if ($this->is_link2($this->characters_staff)) {
				if ($this->link_exists($this->characters_staff)) {
					$this->link_arr = file($this->characters_staff);
					array_walk($this->link_arr, array($this, 'trim'));
				} else {
					http_response_code(404);
					throw new \Exception("Could not access \"".$this->characters_staff."\"", 1);
					return false;
				}
			} else {
				if (file_exists($this->characters_staff)) {
					$this->link_arr = @file($this->characters_staff);
					array_walk($this->link_arr, array($this, 'trim'));
				} else {
					throw new \Exception("File not found \"".$this->characters_staff."\"", 1);
					return false;					
				}
			}

			$this->unsetSearch();
			$this->data['character'] = array();
			$this->data['staff'] = array();

			if ($this->type == "anime") {
				
				$this->setSearch('character', '~</div>Characters & Voice Actors</h2>~', function() {
					$running = true;
					$i = 0;
					$characters = array();
					while ($running) {
						if (preg_match('~<a name="staff"></a>~', $this->link_arr[$this->lineNo + $i])) {
							$running = false;
						}

						$character = array();

						if (preg_match('~<td valign="top" width="27" class="ac borderClass (bgColor2|bgColor1)">~', $this->link_arr[$this->lineNo + $i])) {
							$i += 3;
							$image = array();
							preg_match('~<img alt="(.*)" width="23" height="32" data-src="(.*)" data-srcset="(.*)" class="lazyload" />~', $this->link_arr[$this->lineNo + $i], $image);
							$character['image'] = trim(substr(explode(",", $image[3])[1], 0, -3));

							$i += 5;
							$name = array();
							preg_match('~<a href="(.*)">(.*)</a>~', $this->link_arr[$this->lineNo + $i], $name);
							$character['url'] = $name[1];
							$character['name'] = $name[2];
							$i += 2;
							$role = array();
							preg_match('~<small>(.*)</small>~', $this->link_arr[$this->lineNo + $i], $role);
							$character['role'] = $role[1];


							$running2 = true;
							$character['voice-actor'] = array();
							while ($running2) {
								if (preg_match('~</table>~', $this->link_arr[$this->lineNo + $i])) {
									$running2 = false;
								}

								if (preg_match('~<td valign="top" align="right" style="padding: 0 4px;" nowrap="">~', $this->link_arr[$this->lineNo + $i])) {
									$i++;
									$name = array();
									preg_match('~<a href="(.*)">(.*)</a>~', $this->link_arr[$this->lineNo + $i], $name);
									$i++;
									$role = array();
									preg_match('~<small>(.*)</small>~', $this->link_arr[$this->lineNo + $i], $role);
									$i += 5;
									$image = array();
									preg_match('~<img alt="(.*)" width="23" height="32" data-src="(.*)" data-srcset="(.*)" class="lazyload" />~', $this->link_arr[$this->lineNo + $i], $image);

									$character['voice-actor'][] = array(
										'name' => $name[2],
										'url' => $name[1],
										'role' => $role[1],
										'image' => trim(substr(explode(",", $image[3])[1], 0, -3))
									);
								}

								$i++;
							}
							$characters[] = $character;
						}

						$i++;
					}

					return $characters;
				});


				$this->setSearch('staff', '~<a name="staff"></a>~', function() {
					$running = true;
					$i = 0;
					$staff = array();
					while ($running) {
						$person = array();
						if (preg_match('~<div class="fl-l">~', $this->link_arr[$this->lineNo + $i])) {
							$running = false;
						}

						if (preg_match('~<table border="0" cellpadding="0" cellspacing="0" width="100%">~', $this->link_arr[$this->lineNo + $i])) {
							$i += 5;
							$match = array();
							preg_match('~<img alt="(.*)" width="23" height="32" data-src="(.*)" data-srcset="(.*)" class="lazyload" />~', $this->link_arr[$this->lineNo + $i], $match);
							$person['image'] = trim(substr(explode(",", $match[3])[1], 0, -3));
							$i += 5;
							$match = array();
							preg_match('~<a href="(.*)">(.*)</a>~', $this->link_arr[$this->lineNo + $i], $match);
							$person['name'] = $match[2];
							$person['url'] = $match[1];
							$i += 2;
							$match = array();
							preg_match('~<small>(.*)</small>~', $this->link_arr[$this->lineNo + $i], $match);
							$person['role'] = $match[1];

							$staff[] = $person;
						}

						$i++;
					}
					return $staff;
				});

			} elseif ($this->type == "manga") {

				$this->setSearch('character', '~</div>Characters</h2>~', function() {
					$running = true;
					$i = 0;
					$characters = array();
					while ($running) {
						if (preg_match('~<div class="fl-r">~', $this->link_arr[$this->lineNo + $i])) {
							$running = false;
						}

						$character = array();

						if (preg_match('~<td valign="top" width="27" class="ac borderClass (bgColor2|bgColor1)">~', $this->link_arr[$this->lineNo + $i])) {
							$i += 3;
							$image = array();
							preg_match('~<img alt="(.*)" width="23" height="32" data-src="(.*)" data-srcset="(.*)" class="lazyload" />~', $this->link_arr[$this->lineNo + $i], $image);
							$character['image'] = trim(substr(explode(",", $image[3])[1], 0, -3));

							$i += 5;
							$name = array();
							preg_match('~<a href="(.*)">(.*)</a>~', $this->link_arr[$this->lineNo + $i], $name);
							$character['url'] = $name[1];
							$character['name'] = $name[2];
							$i += 2;
							$role = array();
							preg_match('~<small>(.*)</small>~', $this->link_arr[$this->lineNo + $i], $role);
							$character['role'] = $role[1];
							$characters[] = $character;
						}

						$i++;
					}

					return $characters;
				});

			}

			foreach ($this->link_arr as $lineNo => $line) {
				$this->line = $line;
				$this->lineNo = $lineNo;

				$this->find();
			}

			unset($this->matches);
			$this->matches = array();

			return $this;
		}

	/*
		Child Method: pictures
		Parameter: None
		Returns: $this
		Parent Methods: anime, manga, character, people
	*/
		public function pictures() {}

	/*
		Child Method: more_info
		Parameter: None
		Returns: $this
		Parent Methods: anime
	*/
		public function more_info() {}



/*
	Utility Methods
*/

		public function json() {
			if ($this->data !== false) {
				return json_encode($this->data);
			}
		}

/*
	Object Methods
*/
		public function find() {
			foreach ($this->search as $index => $arr) {
				if (!$arr['found']) {
					if (preg_match($arr['regex'], $this->line, $this->matches)) {
						if ($arr['merge']) {
							$this->data[$index] = array_merge($this->data[$index], ($arr['args'] !== false) ? $arr['func'](...$arr['args']) : $arr['func']());
						} else {
							$this->data[$index] = ($arr['args'] !== false) ? $arr['func'](...$arr['args']) : $arr['func']();
						}
						$this->search[$index]['found'] = true;
					}
				}
			}
		}

		public function setParentFile($type, $value) {
			$this->type = $type;
			$this->link = $value;
			return $this;
		}

		public function setChildFile($page, $value) {
			if (!property_exists('\Jikan\Get', $page)) {throw new \Exception("Invalid Extended Path", 1); }
			$this->{$page} = $value;
			return $this;
		}

		/**
		 * @param string $index
		 * @param string $regex
		 * @param \Closure $func
		 */
		private function setSearch($index, $regex, $func, $args = null, $merge = false) {
			$args = is_null($args) ? false : $args;
			$this->search[$index] = array(
				'regex' => $regex,
				'func' => $func,
				'args' => $args,
				'found' => false,
				'merge' => $merge
			);
		}

		private function unsetSearch() {
			$this->search = array();
		}

		public function is_link($string) {
			return (filter_var($string, FILTER_VALIDATE_URL) ? true : false);
		}

		public function is_link2($string) {
			return preg_match('`^http(s)?://`', $string) ? true : false;
		}

		private function link_exists($link) {
			return (substr(get_headers($link)[0], 9, 3) == "200") ? true : false;
		}
		
		private function trim(&$item, $key) { $item = trim($item); }


	}


}

?>
