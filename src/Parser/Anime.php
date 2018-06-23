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

        $this->addRule('producer', '~<span class="dark_text">Producers:</span>~', function() {
            $return = [];
            if (!preg_match('~None found~', $this->file[$this->lineNo + 1])) {
                $array = explode("</a>", $this->file[$this->lineNo + 1]);
                // producers can contain commas, so we can't use that as delimiter. e.g; "Kanetsu Co., LTD." //anime/16067/anime/32951

                foreach ($array as $key => $value) {
                    if (preg_match('~<a href="/(.*)" title="(.*)">([\s\S]*)(</a>|)~', $value)) {
                        preg_match('~<a href="/(.*)" title="(.*)">([\s\S]*)(</a>|)~', $value, $this->matches);
                        $return[] = [
                            'url' => BASE_URL . $this->matches[1],
                            'name' => strip_tags($this->matches[2])
                        ];
                    }
                }
            }

            $this->model->set('Anime', 'producer', $return);
        });
	}
}