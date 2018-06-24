<?php

namespace Jikan\Parser;

use Jikan\Helper\JString as JString;

class Anime extends \Skraypar\Skraypar
{
	public $model;

	public function __construct(&$model) {
		$this->model = &$model;
	}

	public function loadRules() {
		/*
		 * Link Canonical
		 * MAL ID
		 */
		$this->addRule('link_canonical', '~<meta property="og:url" content="(.*?)">~', function() {
			$this->model->set('Anime', 'link_canonical', $this->matches[1]);

            preg_match('~myanimelist.net/(.+)/(.*)/~', $this->model->get('Anime', 'link_canonical'), $this->matches);
            $this->model->set('Anime', 'mal_id', (int) $this->matches[2]);
		});
		/*
		 * Title
		 */
        $this->addRule('title', '~<meta property="og:title" content="(.*?)">~', function() {
        	$this->model->set('Anime', 'title', 
        		JString::cleanse($this->matches[1])
        	);
        });
        /*
         * Title English
         */
        $this->addRule('title_english', '~<span class="dark_text">English:</span> (.*)~', function() {
			$this->model->set('Anime', 'title_english', 
				JString::cleanse($this->matches[1])
			);
        });
        /*
         * Title Synonyms
         */
        $this->addRule('title_synonyms', '~<span class="dark_text">Synonyms:</span> (.*)~', function() {
			$this->model->set('Anime', 'title_synonyms', 
				JString::cleanse($this->matches[1])
			);
        });
        /*
         * Title Japanese
         */
        $this->addRule('title_japanese', '~<span class="dark_text">Japanese:</span> (.*)~', function() {
            $this->model->set('Anime', 'title_japanese', 
            	JString::cleanse($this->matches[1])
            );
        });
        /*
         * Image URL
         */
        $this->addRule('image_url', '~<meta property="og:image" content="(.*?)">~', function() {
            $this->model->set('Anime', 'image_url', 
            	$this->matches[1]
            );
        });
        /*
         * Type
         */
        $this->addRule('type', '~<span class="dark_text">Type:</span>~', function() {
			preg_match('~<a href="(.*?)">(.*?)</a>~', $this->file[$this->lineNo + 1], $this->matches);
			$this->model->set('Anime', 'type', 
				JString::cleanse($this->matches[2])
			);
        });
        /*
         * Episode count
         */
        $this->addRule('episodes', '~<span class="dark_text">Episodes:</span>~', function() {
			$this->model->set('Anime', 'episodes', 
				(int) $this->file[$this->lineNo + 1]
			);
        });
        /*
         * Status
         * Airing
         */
        $this->addRule('status', '~<span class="dark_text">Status:</span>~', function() {
			$this->model->set('Anime', 'status', 
				JString::cleanse($this->file[$this->lineNo + 1])
			);

			if (strpos($this->model->get('Anime', 'status'), "Currently Airing") !== false) {
				$this->model->set('Anime', 'airing', true);
			}
        });
        /*
         * Aired String
         * Aired ISO8601
         */
        $this->addRule('aired', '~<span class="dark_text">Aired:</span>~', function() {
            $this->model->set('Anime', 'aired_string', 
            	JString::cleanse($this->file[$this->lineNo + 1])
            );


            if (!empty($this->model->get('Anime', 'aired_string')) && $this->model->get('Anime', 'aired_string') != 'Not available') {
                if (strpos($this->model->get('Anime', 'aired_string'), 'to')) {
                    preg_match('~(.*) to (.*)~', $this->model->get('Anime', 'aired_string'), $this->matches);
                    $this->model->set('Anime', 'aired', [
                        'from' => (strpos($this->matches[1], '?') !== false) ? null : @date_format(date_create($this->matches[1]), 'o-m-d'),
                        'to' => (strpos($this->matches[2], '?') !== false) ? null : @date_format(date_create($this->matches[2]), 'o-m-d')
                    ]);
                } else {

                    if (preg_match('~^[0-9]{4}$~', $this->model->get('Anime', 'aired_string')) ||
                        preg_match('~^[A-Za-z]{1,}, [0-9]{4}$~', $this->model->get('Anime', 'aired_string'))) {
                        $this->model->set('Anime', 'aired', [
                            'from' => null,
                            'to' => null
                        ]);                            
                    } else {
                        $this->model->set('Anime', 'aired', [
                            'from' => (strpos($this->model->get('Anime', 'aired_string'), '?') !== false) ? null : @date_format(date_create($this->model->get('Anime', 'aired_string')), 'o-m-d'),
                            'to' => (strpos($this->model->get('Anime', 'aired_string'), '?') !== false) ? null : @date_format(date_create($this->model->get('Anime', 'aired_string')), 'o-m-d')
                        ]);
                    }
                }
            } else {
                $this->model->set('Anime', 'aired', [
                    'from' => null,
                    'to' => null
                ]);
            }
        });
        /*
         * Premiered
         */
        $this->addRule('premiered', '~<span class="dark_text">Premiered:</span>~', function() {
            if (preg_match('~<a href="(.*)">(.*)</a>~', $this->file[$this->lineNo + 1], $this->matches)) {
                $this->model->set('Anime', 'premiered', 
                	JString::cleanse($this->matches[2])
                );
            }
        });
        /*
         * Broadcase
         */
        $this->addRule('broadcast', '~<span class="dark_text">Broadcast:</span>~', function() {
            $this->model->set('Anime', 'broadcast', 
            	JString::cleanse($this->file[$this->lineNo + 1])
            );
        });
        /*
         * Producers
         */
        $this->addRule('producer', '~<span class="dark_text">Producers:</span>~', function() {
        	$return = [];
        	if (!strpos($this->file[$this->lineNo + 1], 'None found')) {
	        	preg_match_all('~<a href="(.*?)" title="(.*?)">(.*?)</a>~', $this->file[$this->lineNo + 1], $this->matches);
	
	        	foreach ($this->matches[1] as $key => $value) {
	        		if (!empty($value)) {
	        			$return[] = [
	        				'url' => BASE_URL . substr($value, 1),
	        				'name' => JString::cleanse($this->matches[3][$key])
	        			];
	        		}
	        	}
        	}

            $this->model->set('Anime', 'producer', $return);
        });
        /*
         * Licensors
         */
        $this->addRule('licensor', '~<span class="dark_text">Licensors:</span>~', function() {
        	$return = [];
        	if (!strpos($this->file[$this->lineNo + 1], 'None found')) {
	        	preg_match_all('~<a href="(.*?)" title="(.*?)">(.*?)</a>~', $this->file[$this->lineNo + 1], $this->matches);
	
	        	foreach ($this->matches[1] as $key => $value) {
	        		if (!empty($value)) {
	        			$return[] = [
	        				'url' => BASE_URL . substr($value, 1),
	        				'name' => JString::cleanse($this->matches[3][$key])
	        			];
	        		}
	        	}
        	}

            $this->model->set('Anime', 'licensor', $return);
        });
        /*
         * Studios
         */
        $this->addRule('studio', '~<span class="dark_text">Studios:</span>~', function() {
        	$return = [];
        	if (!strpos($this->file[$this->lineNo + 1], 'None found')) {
	        	preg_match_all('~<a href="(.*?)" title="(.*?)">(.*?)</a>~', $this->file[$this->lineNo + 1], $this->matches);
	
	        	foreach ($this->matches[1] as $key => $value) {
	        		if (!empty($value)) {
	        			$return[] = [
	        				'url' => BASE_URL . substr($value, 1),
	        				'name' => JString::cleanse($this->matches[3][$key])
	        			];
	        		}
	        	}
        	}

            $this->model->set('Anime', 'studio', $return);
        });
        /*
         * Source
         */
        $this->addRule('source', '~<span class="dark_text">Source:</span>~', function() {
            $this->model->set('Anime', 'source', 
            	JString::cleanse($this->file[$this->lineNo + 1])
            );
        });
        /*
         * Genres
         */
        $this->addRule('genre', '~<span class="dark_text">Genres:</span>~', function() {
        	$return = [];
        	if (!strpos($this->file[$this->lineNo + 1], 'None found')) {
	        	preg_match_all('~<a href="(.*?)" title="(.*?)">(.*?)</a>~', $this->file[$this->lineNo + 1], $this->matches);
	
	        	foreach ($this->matches[1] as $key => $value) {
	        		if (!empty($value)) {
	        			$return[] = [
	        				'url' => BASE_URL . substr($value, 1),
	        				'name' => JString::cleanse($this->matches[3][$key])
	        			];
	        		}
	        	}
        	}

            $this->model->set('Anime', 'genre', $return);
        });
        /*
         * Duration
         */
        $this->addRule('duration', '~<span class="dark_text">Duration:</span>~', function() {
			$this->model->set('Anime', 'duration', 
				JString::cleanse($this->file[$this->lineNo + 1])
			);
        });
        /*
         * Rating
         */
        $this->addRule('rating', '~<span class="dark_text">Rating:</span>~', function() {
			$this->model->set('Anime', 'rating', 
				JString::cleanse($this->file[$this->lineNo + 1])
			);
        });
        /*
         * Score
         */
        $this->addRule('score', '~<span class="dark_text">Score:</span>~', function(){
            preg_match('~<span(.*?)>(.*)</span><sup>1</sup> \(scored by <span(.*?)>(.*)</span> users\)~', $this->file[$this->lineNo + 1], $this->matches);

            $this->model->set('Anime', 'score', (float) $this->matches[2]);
            $this->model->set('Anime', 'scored_by', (int) str_replace(",", "", $this->matches[4]));
        });
        /*
         * Rank
         */
        $this->addRule('rank', '~<span class="dark_text">Ranked:</span>~', function() {
           if (!preg_match('~N/A<sup>2</sup>~', $this->file[$this->lineNo + 1], $this->matches)) {
               preg_match('~#(.*)<sup>2</sup>~', $this->file[$this->lineNo + 1], $this->matches);

               $this->model->set('Anime', 'rank', (int) $this->matches[1]);
           }
        });
        /*
         * Popularity
         */
        $this->addRule('popularity', '~<span class="dark_text">Popularity:</span>~', function() {
           preg_match('~#(.*)~', $this->file[$this->lineNo + 1], $this->matches);

           $this->model->set('Anime', 'popularity', (int) $this->matches[1]);
        });
        /*
         * Members
         */
        $this->addRule('members', '~<span class="dark_text">Members:</span>~', function() {
           $this->model->set('Anime', 'members', (int) str_replace(",", "", $this->file[$this->lineNo + 1]));
        });
        /*
         * Favorites
         */
        $this->addRule('favorites', '~<span class="dark_text">Favorites:</span>~', function() {
            $this->model->set('Anime', 'favorites', (int) str_replace(",", "", $this->file[$this->lineNo + 1]));
        });
        /*
         * Synopsis
         */
        $this->addRule('synopsis', '~<meta property="og:description" content="(.*)">~', function() {
            $this->model->set('Anime', 'synopsis', 
            	JString::cleanse($this->matches[1])
            );
        });
        /*
         * Related
         */
        $this->addRule('related', '~<table class="anime_detail_related_anime"~', function() {
            $return = [];

            
            $line = $this->file[$this->lineNo];
            $line = substr($line, strpos($line, '<td nowrap="" valign="top" class="ar fw-n borderClass">'));
            $line = explode('<td nowrap="" valign="top" class="ar fw-n borderClass">', $line);
            foreach ($line as $key => $value) {
            	if (!empty($value)) {
		            preg_match_all('~(.*?)</td>(.*?)</td>(|</table>)~', $value, $this->matches);
		            $title = substr($this->matches[1][0], 0, -1);
		            if (!isset($return[$title])){
		            	$return[$title] = [];
		            }

	        		preg_match_all('~<a href="(/(.*?)/(.*?)/(.*?))">(.*?)</a>~', $this->matches[2][0], $this->matches);

	        		foreach ($this->matches[1] as $key => $value) {
	        			if (!empty($value)) {
			        		$return[$title][] = [
			        			'mal_id' => (int) $this->matches[3][$key],
			        			'type' => $this->matches[2][$key],
			        			'url' => BASE_URL . substr($this->matches[1][$key], 1),
			        			'title' => $this->matches[5][$key]
			        		];
	        			}
	        		}
            	}
            }

            $this->model->set('Anime', 'related', $return);
        });
        /*
         * Background
         */
        $this->addRule('background', '~</div>Background</h2>~', function() {
			if (!preg_match('~No background information has been added to this title.~', $this->line)) {
				if (preg_match('~</div>Background</h2>([\s\S]*)<div class="border_top~', $this->line, $this->matches)) {
					$this->model->set('Anime', 'background', 
						JString::cleanse($this->matches[1])
					);
               	} else {

					$background = (new \Skraypar\Iterator(
						$this->file, 
						$this->lineNo
                   	))->setBreakpointPattern([
						'~<div class="border_top"~',
						'~</td></tr><tr><td class="pb24">~'
                  	]);

                  	$background->setIteratorCallable(function() use (&$background, &$string) {
                  		$string .= $background->getLine();
                   	})->parse(true);


                    if (strpos($string, '</td></tr><tr><td class="pb24">')) {
                        $string = substr($string, 0, strpos($string, '</td></tr><tr><td class="pb24">'));
                    }
                    if (strpos($string, '<div class="border_top"')) {
                        $string = substr($string, 0, strpos($string, '<div class="border_top"'));
                    }

                    $string = substr($string, strpos($string, 'Background</h2>') + strlen('Background</h2>'));

                    echo JString::cleanse($string);

                	$this->model->set('Anime', 'background', 
                		JString::cleanse($string)
                	);
               }
           }
        });        
        /*
         * Opening Themes
         */
        $this->addRule('opening_theme', '~<div class="theme-songs js-theme-songs opnening">([\s\S]*)</div>~', function() {
            preg_match_all('~<span class="theme-song">(.*?)</span>~', $this->matches[1], $this->matches);
            foreach ($this->matches[1] as $key => &$value) { $value = JString::cleanse($value); }
           
            $this->model->set('Anime', 'opening_theme', $this->matches[1]);
        });
        /*
         * Ending Themes
         */
        $this->addRule('ending_theme', '~<div class="theme-songs js-theme-songs ending">([\s\S]*)</div>~', function() {
            preg_match_all('~<span class="theme-song">(.*?)</span>~', $this->matches[1], $this->matches);
            foreach ($this->matches[1] as $key => &$value) { $value = JString::cleanse($value); }
            
            $this->model->set('Anime', 'ending_theme', $this->matches[1]);
        });
	}
}