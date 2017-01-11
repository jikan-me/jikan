<?php
/**
*	MyAnimeList Unofficial API @version 0.0.0 alpha
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

		public function manga($id) {}

		public function character($id) {}

		public function person($id) {}


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

}

namespace AUTH {}
?>