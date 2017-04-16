<?php
/**
*	MyAnimeList Unofficial API @version 0.1.1 alpha
*	Developed by Nekomata | irfandahir.com
*	
*	This is an unofficial MAL API that provides the features that the official one lacks.
*	This UAPI scraps web pages, parses the data you require from them and returns it back as a PHP/JSON array.
*	Therefore, no authentication is needed for fetching anime data, search results, etc.
*/

namespace MAL {

	class GET {

		public $last_error = "";
		public $types = array(
			"anime" => "https://myanimelist.net/anime/",
			"manga" => "https://myanimelist.net/manga/",
			"people" => "https://myanimelist.net/people/",
			"character" => "https://myanimelist.net/character/"
		);

		public $link = false;
		public $link_arr = false;
		public $data = false;
		protected $type = false;

		private $search = array();
		private $line = false;
		private $lineNo = false;
		private $matches = array();


		public function anime($id) {
			$this->link = $this->types["anime"].$id;
			$this->type = "anime";

			if ($this->link_exists($this->link)) {
				$this->link_arr = @file($this->link);
				array_walk($this->link_arr, array($this, 'trim'));
			} else {
				$this->log("Error: Could not access \"".$this->link."\"");
				return false;
			}

			if (!empty($this->data)) {
				unset($this->data);
				$this->data = arary();
			}

			$this->setSearch("link_canonical", "/<link rel=\"canonical\" href=\"(.*)\" \/>/", function(){
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

			$this->data = array();

			foreach ($this->link_arr as $lineNo => $line) {
				$this->line = $line;
				$this->lineNo = $lineNo;
				
				$this->find();
			}

			unset($this->matches);
			$this->matches = array();

			$this->data = (empty($this->data)) ? false : $this->data;

			return $this->data;
		}

		public function manga($id) {
			$this->link = $this->types["manga"].$id;
			$this->type = "manga";

			if ($this->link_exists($this->link)) {
				$this->link_arr = @file($this->link);
				array_walk($this->link_arr, array($this, 'trim'));
			} else {
				$this->log("Error: Could not access \"".$this->link."\"");
				return false;
			}

			if (!empty($this->data)) {
				unset($this->data);
				$this->data = arary();
			}

			$this->setSearch("link_canonical", "/<link rel=\"canonical\" href=\"(.*)\" \/>/", function(){
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
			//todo
			//var_dump($this->search);
			//die();

			$this->data = array();

			foreach ($this->link_arr as $lineNo => $line) {
				$this->line = $line;
				$this->lineNo = $lineNo;
				
				$this->find();
			}

			unset($this->matches);
			$this->matches = array();

			$this->data = (empty($this->data)) ? false : $this->data;

			return $this->data;			
		}

		public function character($id) {}

		public function person($id) {}


		public function videos() {}
		public function episodes() {}
		public function reviews() {}
		public function recommendations() {}
		public function stats() {}
		public function characters() {}
		public function news() {}
		public function forum() {}
		public function featured() {}
		public function clubs() {}
		public function pictures() {}
		public function moreinfo() {}


		public function json() {
			if ($this->data !== false) {
				return json_encode($this->data);
			}
		}

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

		private function log($text) {
			if (PHP_SAPI === 'cli') {
				echo $text."\r";
			}
		}

		private function link_exists($link) {
			return (substr(get_headers($link)[0], 9, 3) == "200") ? true : false;
		}
		
		private function trim(&$item, $key) {$item = trim($item);}

	}



	/**
	*	Tasks that require authentication. Acts as a wrapper using the official API.
	*/
	class AUTH {}

}

?>