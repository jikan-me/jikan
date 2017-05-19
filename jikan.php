<?php
/**
*	Jikan - MyAnimeList Unofficial API @version 0.1.4 alpha
*	Developed by Nekomata | irfandahir.com
*	
*	This is an unofficial MAL API that provides the features that the official one lacks.
*	Jikan scraps web pages through a modular method, parses the data you require from them and returns it back as a PHP/JSON array.
*	Therefore, no authentication is needed for fetching anime, manga, character, people, search result data.
*/

namespace Jikan {

	class Get {

/*
	Base URLs for parsing types
*/
		public $types = array(
			"anime" => "https://myanimelist.net/anime/",
			"manga" => "https://myanimelist.net/manga/",
			"people" => "https://myanimelist.net/people/",
			"character" => "https://myanimelist.net/character/"
		);

/*
	Identifiers for Parsing Meta, Data & Type
*/
		public $link = false;
		public $link_arr = false;
		public $data = false;
		public $type = false;

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
		public function anime($id) {
			$this->link = $this->types["anime"].$id;
			$this->type = "anime";

			if ($this->link_exists($this->link)) {
				$this->link_arr = @file($this->link);
				array_walk($this->link_arr, array($this, 'trim'));
			} else {
				throw new Exception("Error: Could not access \"".$this->link."\"", 1);
				return false;
			}

			if (!empty($this->data)) {
				unset($this->data);
				$this->data = array();
			}

			$this->setSearch("link-canonical", "/<link rel=\"canonical\" href=\"(.*)\" \/>/", function(){
				return $this->matches[1];
			});

			$this->setSearch("title", "#<h1 class=\"h1\"><span itemprop=\"name\">(.*)<\/span><\/h1>#", function(){
				return $this->matches[1];
			});

			$this->setSearch("synonyms", "#<span class=\"dark_text\">Synonyms:<\/span> (.*)#", function(){
				return $this->matches[1];
			});

			$this->setSearch("japanese", "#<span class=\"dark_text\">Japanese:<\/span> (.*)#", function(){
				return $this->matches[1];
			});

			$this->setSearch("image", "#<img src=\"(.*)\" alt=\"(.*)\" class=\"ac\" itemprop=\"image\">#", function(){
				return $this->matches[1];
			});

			$this->setSearch("type", "#<span class=\"dark_text\">Type:<\/span> <a href=\"https://myanimelist.net/topanime.php?type=(.*)\">(.*)<\/a>#", function(){
				return $this->matches[2];
			});

			$this->setSearch("episodes", "#<span class=\"dark_text\">Episodes:<\/span>#", function(){
				return (int) $this->link_arr[$this->lineNo+1];
			});

			$this->setSearch("status", "#<span class=\"dark_text\">Status:<\/span>#", function(){
				return $this->link_arr[$this->lineNo+1];
			});

			$this->setSearch("aired", "#<span class=\"dark_text\">Aired:<\/span>#", function(){
				return $this->link_arr[$this->lineNo+1];
			});

			$this->setSearch("premiered", "#<span class=\"dark_text\">Premiered:<\/span>#", function(){
				$matches = array();
				preg_match("/<a href=\"(.*)\">(.*)<\/a>/", $this->link_arr[$this->lineNo+1], $matches);
				return $matches[2];
			});

			$this->setSearch("broadcast", "#<span class=\"dark_text\">Broadcast:<\/span>#", function(){
				return $this->link_arr[$this->lineNo+1];
			});

			$this->setSearch("producers", "#<span class=\"dark_text\">Producers:<\/span>#", function(){
				$matches = array();
				$return = array();
				if (!preg_match("/None found/", $this->link_arr[$this->lineNo+1])) {
					if (strpos($this->link_arr[$this->lineNo+1], ",")) {
						$arr = explode(",", $this->link_arr[$this->lineNo+1]);
						$data['producers'] = array();
						foreach ($arr as $key => $value) {
							preg_match("#<a href=\"\/anime\/producer\/(.*)\" title=\"(.*)\">(.*)<\/a>#", $value, $matches);
							$return[] = array($matches[1], $matches[3]);
						}
					} else {
						preg_match("#<a href=\"\/anime\/producer\/(.*)\" title=\"(.*)\">(.*)<\/a>#", $this->link_arr[$this->lineNo+1], $matches);
						$return = array($matches[1], $matches[3]);
					}
				} else {
					$return = false;
				}

				return $return;
			});

			$this->setSearch("licensors", "#<span class=\"dark_text\">Licensors:<\/span>#", function(){
				$matches = array();
				$return = array();
				if (!preg_match("/None found/", $this->link_arr[$this->lineNo+1])) {
					if (strpos($this->link_arr[$this->lineNo+1], ",")) {
						$arr = explode(",", $this->link_arr[$this->lineNo+1]);
						foreach ($arr as $key => $value) {
							preg_match("#<a href=\"\/anime\/producer\/(.*)\" title=\"(.*)\">(.*)<\/a>#", $value, $matches);
							$return[] = array($matches[1], $matches[3]);
						}
					} else {
						preg_match("#<a href=\"\/anime\/producer\/(.*)\" title=\"(.*)\">(.*)<\/a>#", $this->link_arr[$this->lineNo+1], $matches);
						$return = array($matches[1], $matches[3]);
					}
				} else {
					$return = false;
				}

				return $return;	
			});

			$this->setSearch("studios", "#<span class=\"dark_text\">Studios:<\/span>#", function(){
				$matches = array();
				$return = array();
				if (!preg_match("/None found/", $this->link_arr[$this->lineNo+1])) {
					if (strpos($this->link_arr[$this->lineNo+1], ",")) {
						$arr = explode(",", $this->link_arr[$this->lineNo+1]);
						foreach ($arr as $key => $value) {
							preg_match("#<a href=\"\/anime\/producer\/(.*)\" title=\"(.*)\">(.*)<\/a>#", $value, $matches);
							$return[] = array($matches[1], $matches[3]);
						}
					} else {
						preg_match("#<a href=\"\/anime\/producer\/(.*)\" title=\"(.*)\">(.*)<\/a>#", $this->link_arr[$this->lineNo+1], $matches);
						$return = array($matches[1], $matches[3]);
					}
				} else {
					$return = false;
				}

				return $return;	
			});

			$this->setSearch("source", "#<span class=\"dark_text\">Source:<\/span>#", function(){
				return $this->link_arr[$this->lineNo+1];
			});

			$this->setSearch("genres", "#<span class=\"dark_text\">Genres:<\/span>#", function(){
					$return = array();
					$matches = array();
				if (strpos($this->link_arr[$this->lineNo+1], ",")) {
					$arr = explode(",", $this->link_arr[$this->lineNo+1]);
					foreach ($arr as $key => $value) {
						preg_match("#<a href=\"\/anime\/genre\/(.*)\" title=\"(.*)\">(.*)<\/a>#", $value, $matches);
						$return[] = array($matches[1], $matches[3]);
					}
				} else {
					preg_match("#<a href=\"\/anime\/genre\/(.*)\" title=\"(.*)\">(.*)<\/a>#", $this->link_arr[$this->lineNo+1], $matches);
					$return = array($matches[1], $matches[3]);
				}

				return $return;
			});

			$this->setSearch("duration", "#<span class=\"dark_text\">Duration:<\/span>#", function(){
				return $this->link_arr[$this->lineNo+1];
			});

			$this->setSearch("rating", "#<span class=\"dark_text\">Rating:<\/span>#", function(){
				return $this->link_arr[$this->lineNo+1];
			});

			$this->setSearch("score", "#<span class=\"dark_text\">Score:<\/span>#", function(){
				$matches = array();
				preg_match("/<span itemprop=\"ratingValue\">(.*)<\/span><sup>1<\/sup> \(scored by <span itemprop=\"ratingCount\">(.*)<\/span> users\)/", $this->link_arr[$this->lineNo+1], $matches);
				return array((float)$matches[1], (int) str_replace(",", "", $matches[2]));
			});

			$this->setSearch("ranked", "#<span class=\"dark_text\">Ranked:<\/span>#", function(){
				$matches = array();
				if (!preg_match("/N\/A<sup>2<\/sup>/", $this->link_arr[$this->lineNo+1], $matches)) {
					preg_match("/#(.*)<sup>2<\/sup>/", $this->link_arr[$this->lineNo+1], $matches);
					return (int) $matches[1];
				} else {
					return false;
				}
			});

			$this->setSearch("popularity", "#<span class=\"dark_text\">Popularity:<\/span>#", function(){
				$matches = array();
				preg_match("/#(.*)/", $this->link_arr[$this->lineNo+1], $matches);
				return (int) $matches[1];				
			});

			$this->setSearch("members", "#<span class=\"dark_text\">Members:<\/span>#", function(){
				return(int) str_replace(",", "", $this->link_arr[$this->lineNo+1]);
			});

			$this->setSearch("favorites", "#<span class=\"dark_text\">Favorites:<\/span>#", function(){
				return (int) str_replace(",", "", $this->link_arr[$this->lineNo+1]);
			});

/*				
	Depreciated Method
			$this->setSearch("synopsis", "/<span itemprop=\"description\">(.*?)/", function(){

				$matches = array();
				$return = "";
				$this->link_arr[$this->lineNo] = preg_replace("#\<br \/\>#", "", $this->line);
				if (preg_match("#<span itemprop=\"description\">(.*)<\/span>#", $this->link_arr[$this->lineNo], $matches)) {
					$return = $matches[1];
				} else {
					$offset = 1;
					preg_match("/<span itemprop=\"description\">(.*)/", $this->line, $matches);
					$return = $matches[1];
					$this->link_arr[$this->lineNo+1] = preg_replace("#\<br \>#", "", $this->link_arr[$this->lineNo+1]);
					while (!preg_match("#(.*)<\/span>#", $this->link_arr[$this->lineNo+$offset])) {
						$return .= $this->link_arr[$this->lineNo+$offset];
						$offset++;
					}
					if (preg_match("/(.*)<\/span>/", $this->line, $matches)) {
						$return .= $matches[1];
					}

					return $return;
				}		
			});
*/		
			$this->setSearch("synopsis", "%<meta property=\"og:description\" content=\"(.*)\">%", function() {
				return $this->matches[1];
			});

			$this->setSearch("external", "/<h2>External Links<\/h2>/", function(){
				$return = array();
				$matches = array();
				if (strpos($this->link_arr[$this->lineNo+1], ",")) {
					$arr = explode(",", $this->link_arr[$this->lineNo+1]);
					foreach ($arr as $key => $value) {
						preg_match("#<a href=\"(.*)\" target=\"_blank\">(.*)<\/a>#", $value, $matches);
						$return[] = array($matches[1], $matches[2]);
					}
				} else {
					preg_match("#<a href=\"(.*)\" target=\"_blank\">(.*)<\/a>#", $this->link_arr[$this->lineNo+1], $matches);
					$return = array($matches[1], $matches[2]);
				}

				return $return;
			});

			/*$this->setSearch("related", "/Related Anime<\/h2>/", function(){
				$return = array();
				$matches = array();
				//todo

				//not going to work cuz regex reads off on different positions, no unique patterns in the source.
				//attempt dirty method
				if (preg_match("#<td nowrap=\"\" valign=\"top\" class=\"ar fw-n borderClass\">Adaptation:<\/td><td width=\"100%\" class=\"borderClass\">(.*)<\/td>#", $this->link_arr[$this->lineNo])) {
					$return["adaption"] = array();
					$matches2 = array();

					preg_match("#<td nowrap=\"\" valign=\"top\" class=\"ar fw-n borderClass\">Adaptation:<\/td><td width=\"100%\" class=\"borderClass\">(.*)<\/td>#", $this->link_arr[$this->lineNo], $matches2);
					var_dump($matches2);
					if (strpos($matches2[2], ",")) {
						$arr = explode(",", $matches2[2]);
						foreach ($arr as $key => $value) {
							preg_match("#<a href=\"(.*)\">(.*)<\/a>#", $value, $matches);
							$return["adaption"][] = array($matches[1], $matches[2]);
						}
					} else {
						preg_match("#<a href=\"(.*)\">(.*)<\/a>#", $matches2[2], $matches);
						$return["adaption"][] = array($matches[1], $matches[2]);
					}
				}

				return $return;
			});*/
			$this->setSearch("related", "~<table class=\"anime_detail_related_anime\"~", function(){
				$return = array();
				$matches = array();
				// i'm sure there's a better way around this... x.x

				$workingLine = $this->link_arr[$this->lineNo];
				$workingLine = substr($workingLine, strpos($workingLine, "<table class=\"anime_detail_related_anime\""));
				$workingLine = substr($workingLine, strpos($workingLine, "<tr>")+4);
				$workingLine = substr($workingLine, 0, strpos($workingLine, "</table>"));

				$workingLine = str_replace("</td>", "</td>,,,,", $workingLine);
				$workingLine = explode(",,,,", $workingLine);


				$title = "";
				foreach ($workingLine as $key => $value) {
					if (empty($value)){unset($workingLine[$key]);}else{
						$tmp = null;
						preg_match("~<td.*?>(.*?)</td>~", $value, $tmp);
						$working = $tmp[1];
						if (preg_match("~<a href=\"(.*)\">(.*)</a>~", $working)) {
							$working2 = explode(",", $working);
							if (count($working2) > 1) {
								foreach ($working2 as $key2 => $value2) {
									$tmp2 = null;
									preg_match("~<a href=\"(.*)\">(.*)</a>~", $value2, $tmp2);
									$return[$title][] = array($tmp2[2], $tmp2[1]);									
								}
							} else {
								preg_match("~<a href=\"(.*)\">(.*)</a>~", $working, $tmp);
								$return[$title] = array($tmp[2], $tmp[1]);
							}
						} else {
							$title = str_replace(":", "", $working);
						}
					}
				}


				return $return;
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
		public function manga($id) {
			$this->link = $this->types["manga"].$id;
			$this->type = "manga";

			if ($this->link_exists($this->link)) {
				$this->link_arr = @file($this->link);
				array_walk($this->link_arr, array($this, 'trim'));
			} else {
				throw new Exception("Error: Could not access \"".$this->link."\"", 1);
				return false;
			}

			if (!empty($this->data)) {
				unset($this->data);
				$this->data = array();
			}

			$this->setSearch("link-canonical", "/<link rel=\"canonical\" href=\"(.*)\" \/>/", function(){
				return $this->matches[1];
			});

			$this->setSearch("title", "#<h1 class=\"h1\"><span itemprop=\"name\">(.*)<\/span><\/h1>#", function(){
				return $this->matches[1];
			});

			$this->setSearch("synonyms", "#<span class=\"dark_text\">Synonyms:<\/span> (.*)#", function(){
				return $this->matches[1];
			});

			$this->setSearch("japanese", "#<span class=\"dark_text\">Japanese:<\/span>(.*?)<\/div>#", function(){
				return trim($this->matches[1]);
			});

			$this->setSearch("image", "#<img src=\"(.*)\" alt=\"(.*)\" itemprop=\"image\" class=\"ac\">#", function(){
				return $this->matches[1];
			});

			$this->setSearch("type", "#<span class=\"dark_text\">Type:<\/span> <a href=\"https://myanimelist.net/topmanga.php?type=(.*)\">(.*)<\/a>#", function(){
				return $this->matches[1];
			});

			$this->setSearch("volumes", "#<span class=\"dark_text\">Volumes:<\/span>(.*)$#", function(){
				return (int) trim($this->matches[1]);
			});

			$this->setSearch("chapters", "#<span class=\"dark_text\">Chapters:<\/span>(.*)$#", function(){
				//return (int) $this->link_arr[$this->lineNo+1];
				return (int) trim($this->matches[1]);
			});

			$this->setSearch("status", "#<span class=\"dark_text\">Status:<\/span>([A-Z-a-z]{1,})<\/div>#", function(){
				return trim($this->matches[1]);
			});

			$this->setSearch("published", "#<span class=\"dark_text\">Published:<\/span>(.*)<\/div>#", function(){
				return trim($this->matches[1]);
			});

			$this->setSearch("genres", "#<span class=\"dark_text\">Genres:<\/span>#", function(){
				$return = array();
				$matches = array();
				if (strpos($this->link_arr[$this->lineNo+1], ",")) {
					$arr = explode(",", $this->link_arr[$this->lineNo+1]);
					foreach ($arr as $key => $value) {
						preg_match("#<a href=\"\/manga\/genre\/(.*)\" title=\"(.*)\">(.*)<\/a>#", $value, $matches);
						$return[] = array($matches[1], $matches[3]);
					}
				} else {
					preg_match("#<a href=\"\/manga\/genre\/(.*)\" title=\"(.*)\">(.*)<\/a>#", $this->link_arr[$this->lineNo+1], $matches);
					$return = array($matches[1], $matches[3]);
				}

				return $return;
			});

			$this->setSearch("authors", "#<span class=\"dark_text\">Authors:<\/span>#", function(){
				$return = array();
				$matches = array();
				if (strpos($this->link_arr[$this->lineNo+1], ">,")) {
					$arr = explode(",", $this->link_arr[$this->lineNo+1]);
					foreach ($arr as $key => $value) {
						preg_match("#<a href=\"(.*)\">(.*)<\/a>(.*?)<\/div>#", $value, $matches);
						$return[] = array($matches[1], $matches[2], trim($matches[3]));
					}
				} else {
					preg_match("#<a href=\"(.*)\">(.*)<\/a>(.*?)<\/div>#", $this->link_arr[$this->lineNo+1], $matches);
					$return = array($matches[1], $matches[2], trim($matches[3]));
				}

				return $return;
			});

			$this->setSearch("serialization", "#<span class=\"dark_text\">Serialization:<\/span>#", function(){
				$return = array();
				$matches = array();
				if (strpos($this->link_arr[$this->lineNo+1], ">,")) {
					$arr = explode(",", $this->link_arr[$this->lineNo+1]);
					foreach ($arr as $key => $value) {
						preg_match("#<a href=\"(.*)\">(.*)<\/a>#", $value, $matches);
						$return[] = array($matches[1], $matches[2]);
					}
				} else {
					preg_match("#<a href=\"(.*)\">(.*)<\/a>#", $this->link_arr[$this->lineNo+1], $matches);
					$return = array($matches[1], $matches[2]);
				}

				return $return;
			});

			// $this->setSearch("score", "#<span class=\"dark_text\">Score:<\/span> <span itemprop=\"ratingValue\">(.*?)<\/span>#", function(){
			// 	$score = (float) $this->matches[1];
			// 	preg_match("#<span itemprop=\"ratingCount\">(.*?)<\/span> users\)<\/small>#", $this->line_arr[$this->lineNo], $this->matches);
			// 	var_dump($this->matches);
			// 	return array((float)$this->matches[1], (int) str_replace(",", "", $this->matches[2]));
			// });

			$this->setSearch("ranked", "~<span class=\"dark_text\">Ranked:<\/span> #(.*[[:alnum:]])<sup>~", function(){
				return ($this->matches[1] == "N/A" ? $this->matches[1] : (int) $this->matches[1]);
			});


			$this->setSearch("popularity", "~<span class=\"dark_text\">Popularity:<\/span> #(.*[[:alnum:]])<\/div>~", function(){
				return ($this->matches[1] == "N/A" ? $this->matches[1] : (int) $this->matches[1]);
			});

			$this->setSearch("members", "#<span class=\"dark_text\">Members:<\/span>(.*)<\/div>#", function(){
				$this->matches[1] = str_replace(",", "", trim($this->matches[1]));
				return (int) $this->matches[1];
			});

			$this->setSearch("favorites", "#<span class=\"dark_text\">Favorites:<\/span>(.*)<\div>#", function(){
				return $this->matches[1];
			});

/*		
	Depreciated	
			$this->setSearch("synopsis", "/<span itemprop=\"description\">(.*?)/", function(){
				$matches = array();
				$return = "";
				$this->link_arr[$this->lineNo] = preg_replace("#\<br \/\>#", "", $this->line);
				if (preg_match("#<span itemprop=\"description\">(.*)<\/span>#", $this->link_arr[$this->lineNo], $matches)) {
					$return = $matches[1];
				} else {
					$offset = 1;
					preg_match("/<span itemprop=\"description\">(.*)/", $this->line, $matches);
					$return = $matches[1];
					$this->link_arr[$this->lineNo+1] = preg_replace("#\<br \>#", "", $this->link_arr[$this->lineNo+1]);
					while (!preg_match("#(.*)<\/span>#", $this->link_arr[$this->lineNo+$offset])) {
						$return .= $this->link_arr[$this->lineNo+$offset];
						$offset++;
					}
					if (preg_match("/(.*)<\/span>/", $this->line, $matches)) {
						$return .= $matches[1];
					}

					return $return;
				}				
			});
*/

			$this->setSearch("external", "/<h2>External Links<\/h2>/", function(){
				$return = array();
				$matches = array();
				if (strpos($this->link_arr[$this->lineNo+1], ",")) {
					$arr = explode(",", $this->link_arr[$this->lineNo+1]);
					foreach ($arr as $key => $value) {
						preg_match("#<a href=\"(.*)\" target=\"_blank\">(.*)<\/a>#", $value, $matches);
						$return[] = array($matches[1], $matches[2]);
					}
				} else {
					preg_match("#<a href=\"(.*)\" target=\"_blank\">(.*)<\/a>#", $this->link_arr[$this->lineNo+1], $matches);
					$return = array($matches[1], $matches[2]);
				}

				return $return;
			});

			$this->setSearch("related", "~<table class=\"anime_detail_related_anime\"~", function(){
				$return = array();
				$matches = array();
				// i'm sure there's a better way around this... x.x

				$workingLine = $this->link_arr[$this->lineNo];
				$workingLine = substr($workingLine, strpos($workingLine, "<table class=\"anime_detail_related_anime\""));
				$workingLine = substr($workingLine, strpos($workingLine, "<tr>")+4);
				$workingLine = substr($workingLine, 0, strpos($workingLine, "</table>"));

				$workingLine = str_replace("</td>", "</td>,,,,", $workingLine);
				$workingLine = explode(",,,,", $workingLine);


				$title = "";
				foreach ($workingLine as $key => $value) {
					if (empty($value)){unset($workingLine[$key]);}else{
						$tmp = null;
						preg_match("~<td.*?>(.*?)</td>~", $value, $tmp);
						$working = $tmp[1];
						if (preg_match("~<a href=\"(.*)\">(.*)</a>~", $working)) {
							$working2 = explode(",", $working);
							if (count($working2) > 1) {
								foreach ($working2 as $key2 => $value2) {
									$tmp2 = null;
									preg_match("~<a href=\"(.*)\">(.*)</a>~", $value2, $tmp2);
									$return[$title][] = array($tmp2[2], $tmp2[1]);									
								}
							} else {
								preg_match("~<a href=\"(.*)\">(.*)</a>~", $working, $tmp);
								$return[$title] = array($tmp[2], $tmp[1]);
							}
						} else {
							$title = str_replace(":", "", $working);
						}
					}
				}


				return $return;
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
				throw new Exception("Error: Could not access \"".$this->link."\"", 1);
				return false;
			}

			if (!empty($this->data)) {
				unset($this->data);
				$this->data = array();
			}

			$this->setSearch("name", "~<div class=\"normal_header\" style=\"height: 15px;\">(.*) <span style=\"font-weight: normal;\"><small>(.*)</small></span></div>~", function(){
				return $this->matches[1];
			});

			$this->setSearch("name-japanese", "~<div class=\"normal_header\" style=\"height: 15px;\">(.*) <span style=\"font-weight: normal;\"><small>(.*)</small></span></div>~", function(){
				return $this->matches[2];
			});

			$this->setSearch("about", "~<div class=\"normal_header\" style=\"height: 15px;\">(.*) <span style=\"font-weight: normal;\"><small>(.*)</small></span></div>([\s\S]*)~", function(){
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
					$line = $this->link_arr[$this->lineNo+$i];
					if (preg_match("~</table>~", $line)) {
						$running = false;
					}

					if (preg_match("~<tr>~", $line)) {
						$i++;
						$animeMeta = array();
						preg_match('~<td width="25" class="borderClass" valign="top"><div class="picSurround"><a href="(.*)"><img src="(.*)" border="0"></a></div></td>~', $this->link_arr[$this->lineNo+$i], $animeMeta);
						$i++;
						$animeName = array();
						preg_match('~<td valign="top" class="borderClass"><a href="(.*)">(.*)</a>~', $this->link_arr[$this->lineNo+$i], $animeName);
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
					$line = $this->link_arr[$this->lineNo+$i];
					if (preg_match("~</table>~", $line)) {
						$running = false;
					}

					if (preg_match("~<tr>~", $line)) {
						$i++;
						$mangaMeta = array();
						preg_match('~<td width="25" class="borderClass" valign="top"><div class="picSurround"><a href="(.*)"><img src="(.*)" border="0"></a></div></td>~', $this->link_arr[$this->lineNo+$i], $mangaMeta);
						$i++;
						$mangaName = array();
						preg_match('~<td valign="top" class="borderClass"><a href="(.*)">(.*)</a>~', $this->link_arr[$this->lineNo+$i], $mangaName);
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
				while($running) {
					$line = $this->link_arr[$this->lineNo+$i]; // bugs
					if (
						preg_match('~<h2><div class="floatRightHeader">~', $line) ||
						preg_match('~<div class="mauto clearfix pt24" style="width:760px;">~', $line)
						) {
						$running = false;
					}

					if (preg_match("~<tr>~", $line)) {
						$i++;
						$personMeta = array();
						preg_match('~<td class="borderClass" valign="top" width="25"><div class="picSurround"><a href="(.*)"><img src="(.*)" border="0"></a></div></td>~', $this->link_arr[$this->lineNo+$i], $personMeta);
						$i++;
						$personName = array();
						preg_match('~<td class="borderClass" valign="top"><a href="(.*)">(.*)</a>~', $this->link_arr[$this->lineNo+$i], $personName);
						$i++;
						$personType = array();
						preg_match('~<div style="margin-top: 2px;"><small>(.*)</small></div>~', $this->link_arr[$this->lineNo+$i], $personType);
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
				return (int)str_replace(',', '', trim($this->matches[1]));
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
				throw new Exception("Error: Could not access \"".$this->link."\"", 1);
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
				return (int)str_replace(',', '', $this->matches[1]);
			});

			$this->setSearch('more', '~<div class="people-informantion-more js-people-informantion-more">([\s\S]*)</div>~', function() {
				return $this->matches[1];
			});

			$this->setSearch('voice-acting-role', '~</div>Voice Acting Roles</div>~', function() {
				$running = true;
				$i = 1;
				$voiceActingRoles = array();
				while($running) {
					$line = $this->link_arr[$this->lineNo+$i];
					if (preg_match('~</span>Anime Staff Positions</div>~', $line)) {
						$running = false;
					}

					if (preg_match("~<tr>~", $line)) {
						$i++;
						$animeMeta = array();
						preg_match('~<td valign="top" class="borderClass" width="25"><div class="picSurround"><a href="(.*)"><img data-src="(.*)" border="0" width="23" class="lazyload"></a></div></td>~', $this->link_arr[$this->lineNo+$i], $animeMeta);
						$i++;
						$animeName = array();
						preg_match('~<td valign="top" class="borderClass"><a href="(.*)">(.*)</a><div class="spaceit_pad">~', $this->link_arr[$this->lineNo+$i], $animeName);
						$i += 2;
						$char = array();
						preg_match('~<td valign="top" class="borderClass" align="right" nowrap><a href="(.*)">(.*)</a>&nbsp;<div class="spaceit_pad">(.*)&nbsp;</div></td>~', $this->link_arr[$this->lineNo+$i], $char);
						$i++;
						$charMeta = array();
						preg_match('~<td valign="top" class="borderClass" width="25"><div class="picSurround"><a href="(.*)"><img data-src="(.*)" border="0" alt="(.*)" width="23" class="lazyload"></a></div></td>~', $this->link_arr[$this->lineNo+$i], $charMeta);
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
				while($running) {
					$line = $this->link_arr[$this->lineNo+$i];
					if (preg_match('~</span>Published Manga</div>~', $line)) {
						$running = false;
					}

					if (preg_match("~<tr>~", $line)) {
						$i++;
						$animeMeta = array();
						preg_match('~<td valign="top" class="borderClass" width="25"><div class="picSurround"><a href="(.*)"><img data-src="(.*)" border="0" width="23" class="lazyload"></a></div></td>~', $this->link_arr[$this->lineNo+$i], $animeMeta);
						$i++;
						$animeName = array();
						preg_match('~<td valign="top" class="borderClass"><a href="(.*)">(.*)</a><div class="spaceit_pad">~', $this->link_arr[$this->lineNo+$i], $animeName);
						$i++;
						$role = array();
						preg_match('~<a href="(.*)" title="Quick add anime to my list" class="button_add">add</a> <small>(.*)</small>~', $this->link_arr[$this->lineNo+$i], $role);
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
				while($running) {
					$line = $this->link_arr[$this->lineNo+$i];
					if (preg_match('~</table>~', $line)) {
						$running = false;
					}

					if (preg_match("~<tr>~", $line)) {
						$i++;
						$mangaMeta = array();
						preg_match('~<td valign="top" class="borderClass" width="25"><div class="picSurround"><a href="(.*)"><img data-src="(.*)" border="0" width="23" class="lazyload"></a></div></td>~', $this->link_arr[$this->lineNo+$i], $mangaMeta);
						$i++;
						$mangaName = array();
						preg_match('~<td valign="top" class="borderClass"><a href="(.*)">(.*)</a><div class="spaceit_pad">~', $this->link_arr[$this->lineNo+$i], $mangaName);
						$i++;
						$role = array();
						preg_match('~<a href="(.*)" title="Quick add manga to my list" class="button_add">add</a> <small>(.*)</small>~', $this->link_arr[$this->lineNo+$i], $role);
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
	public function search($query, $page = 1) {

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
		public function episodes() {}
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
		Child Method: recommendations
		Parameter: None
		Returns: $this
		Parent Methods: anime, manga
	*/
		public function stats() {}
	/*
		Child Method: stats
		Parameter: None
		Returns: $this
		Parent Methods: anime, manga
	*/
		public function characters_staff() {}
	/*
		Child Method: characters_staff
		Parameter: None
		Returns: $this
		Parent Methods: anime, manga
	*/
		public function pictures() {}
	/*
		Child Method: pictures
		Parameter: None
		Returns: $this
		Parent Methods: anime, manga, character, people
	*/
		public function more_info() {}
	/*
		Child Method: more_info
		Parameter: None
		Returns: $this
		Parent Methods: anime
	*/


/*
	Unplanned Features
*/
		public function news() {}
		public function forum() {}
		public function featured() {}
		public function clubs() {}

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
						$this->data[$index] = ($arr['args'] !== false) ? $arr['func'](...$arr['args']) : $arr['func']();
						$this->search[$index]['found'] = true;
					}
				}
			}
		}

		private function getFind(){ return $this->last_find; }

		private function setSearch($index, $regex, $func, $args=null) {
			$this->search[$index] = (!is_null($args)) ? array('regex'=>$regex, 'func'=>$func, 'args'=>$args) : array('regex'=>$regex, 'func'=>$func, 'args'=>false, 'found'=>false);
		}

		private function link_exists($link) {
			return (substr(get_headers($link)[0], 9, 3) == "200") ? true : false;
		}
		
		private function trim(&$item, $key) { $item = trim($item); }

	}



}

?>
