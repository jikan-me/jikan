<?php

namespace Jikan\Lib\Parser;

use Jikan\Model\Anime as AnimeModel;

class AnimeParse extends TemplateParse
{

    public function parse() {

        $this->model = new AnimeModel;

        /*
         * Rules
         */

        $this->addRule('link_canonical', '~<link rel="canonical" href="(.*)" />~', function() {
            $this->model->set('Anime', 'link_canonical', $this->matches[1]);

            preg_match('~myanimelist.net/(.+)/(.*)/~', $this->model->get('Anime', 'link_canonical'), $this->matches);
            $this->model->set('Anime', 'mal_id', (int) $this->matches[2]);
        });

        $this->addRule('title', '~<h1 class="h1"><span itemprop="name">(.*)</span></h1>~', function() {
           $this->model->set('Anime', 'title', htmlspecialchars_decode($this->matches[1]));
        });

        $this->addRule('title_english', '~<span class="dark_text">English:</span> (.*)~', function() {
            $this->model->set('Anime', 'title_english', htmlspecialchars_decode($this->matches[1]));
        });

        $this->addRule('title_synonyms', '~<span class="dark_text">Synonyms:</span> (.*)~', function() {
            $this->model->set('Anime', 'title_synonyms', htmlspecialchars_decode($this->matches[1]));
        });

        $this->addRule('title_japanese', '~<span class="dark_text">Japanese:</span> (.*)~', function() {
            $this->model->set('Anime', 'title_japanese', htmlspecialchars_decode($this->matches[1]));
        });

        $this->addRule('image_url', '~<img src="(.*)" alt="(.*)" class="ac" itemprop="image">~', function() {
            $this->model->set('Anime', 'image_url', $this->matches[1]);
        });

        $this->addRule('type', '~<span class="dark_text">Type:</span>~', function() {
            preg_match('~(<a href="(.*)">(.*?)</a>|(.*))</div>~', $this->file[$this->lineNo + 1], $this->matches);
            $this->model->set('Anime', 'type', (empty($this->matches[3]) ? $this->matches[4] : $this->matches[3]));
        });

        $this->addRule('episodes', '~<span class="dark_text">Episodes:</span>~', function() {
            $this->model->set('Anime', 'episodes', (int) $this->file[$this->lineNo + 1]);
        });

        $this->addRule('status', '~<span class="dark_text">Status:</span>~', function() {
            $this->model->set('Anime', 'status', $this->file[$this->lineNo + 1]);

            if (strpos($this->model->get('Anime', 'status'), "Currently Airing") !== false)
                $this->model->set('Anime', 'airing', true);
        });

        $this->addRule('aired', '~<span class="dark_text">Aired:</span>~', function() {
            $this->model->set('Anime', 'aired_string', $this->file[$this->lineNo + 1]);


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

        $this->addRule('premiered', '~<span class="dark_text">Premiered:</span>~', function() {
            if (preg_match('~<a href="(.*)">(.*)</a>~', $this->file[$this->lineNo + 1], $this->matches)) {
                $this->model->set('Anime', 'premiered', $this->matches[2]);
            }
        });

        $this->addRule('broadcast', '~<span class="dark_text">Broadcast:</span>~', function() {
            $this->model->set('Anime', 'broadcast', $this->file[$this->lineNo + 1]);
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

        $this->addRule('licensor', '~<span class="dark_text">Licensors:</span>~', function() {
            $return = [];
            if (!preg_match('~None found~', $this->file[$this->lineNo + 1])) {
                $array = explode("</a>", $this->file[$this->lineNo + 1]);
                // licensors can contain commas, so we can't use that as delimiter. e.g; "NIS America, Inc." //anime/16067

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

            $this->model->set('Anime', 'licensor', $return);
        });

        $this->addRule('studio', '~<span class="dark_text">Studios:</span>~', function() {
            $return = [];
            if (!preg_match('~None found~', $this->file[$this->lineNo + 1])) {
                $array = explode("</a>", $this->file[$this->lineNo + 1]);

                foreach ($array as $key => $value) {
                    preg_match('~<a href="/(.*)" title="(.*)">([\s\S]*)(</a>|)~', $value, $this->matches);

                    if (!empty($this->matches)) {
                        $return[] = [
                            'url' => BASE_URL . $this->matches[1],
                            'name' => strip_tags($this->matches[2])
                        ];
                    }
                }
            }

            $this->model->set('Anime', 'studio', $return);
        });

        $this->addRule('source', '~<span class="dark_text">Source:</span>~', function() {
            $this->model->set('Anime', 'source', $this->file[$this->lineNo + 1]);
        });

        $this->addRule('genre', '~<span class="dark_text">Genres:</span>~', function() {
            $return = [];
            if (!preg_match('~No genres have been added yet~', $this->file[$this->lineNo + 1])) {
                $array = explode(",", $this->file[$this->lineNo + 1]);

                foreach ($array as $key => $value) {
                    preg_match('~<a href="/(.*)" title="(.*)">([\s\S]*)(</a>|)~', $value, $this->matches);
                    $return[] = [
                        'url' => BASE_URL . $this->matches[1],
                        'name' => strip_tags($this->matches[2])
                    ];
                }
            }

            $this->model->set('Anime', 'genre', $return);
        });

        $this->addRule('duration', '~<span class="dark_text">Duration:</span>~', function() {
           $this->model->set('Anime', 'duration', $this->file[$this->lineNo + 1]);
        });

        $this->addRule('rating', '~<span class="dark_text">Rating:</span>~', function() {
           $this->model->set('Anime', 'rating', htmlspecialchars_decode($this->file[$this->lineNo + 1]));
        });

        $this->addRule('score', '~<span class="dark_text">Score:</span>~', function(){
            preg_match('~<span(.*?)>(.*)</span><sup>1</sup> \(scored by <span(.*?)>(.*)</span> users\)~', $this->file[$this->lineNo + 1], $this->matches);

            $this->model->set('Anime', 'score', (float) $this->matches[2]);
            $this->model->set('Anime', 'scored_by', (int) str_replace(",", "", $this->matches[4]));
        });

        $this->addRule('rank', '~<span class="dark_text">Ranked:</span>~', function() {
           if (!preg_match('~N/A<sup>2</sup>~', $this->file[$this->lineNo + 1], $this->matches)) {
               preg_match('~#(.*)<sup>2</sup>~', $this->file[$this->lineNo + 1], $this->matches);

               $this->model->set('Anime', 'rank', (int) $this->matches[1]);
           }
        });

        $this->addRule('popularity', '~<span class="dark_text">Popularity:</span>~', function() {
           preg_match('~#(.*)~', $this->file[$this->lineNo + 1], $this->matches);

           $this->model->set('Anime', 'popularity', (int) $this->matches[1]);
        });

        $this->addRule('members', '~<span class="dark_text">Members:</span>~', function() {
           $this->model->set('Anime', 'members', (int) str_replace(",", "", $this->file[$this->lineNo + 1]));
        });

        $this->addRule('favorites', '~<span class="dark_text">Favorites:</span>~', function() {
            $this->model->set('Anime', 'favorites', (int) str_replace(",", "", $this->file[$this->lineNo + 1]));
        });

        $this->addRule('synopsis', '~<meta property="og:description" content="(.*)">~', function() {
            $this->model->set('Anime', 'synopsis', htmlspecialchars_decode($this->matches[1]));
        });

        $this->addRule('related', '~<table class="anime_detail_related_anime"~', function() {
            $return = [];

            $line = $this->file[$this->lineNo];
            $line = substr($line, strpos($line, '<table class="anime_detail_related_anime"'));
            $line = substr($line, strpos($line, '<tr>') + 4);
            $line = substr($line, 0, strpos($line, '</table>'));
            $line = str_replace('</td>', '</td>,,,,', $line);
            $related = explode(',,,,', $line);


            $title = '';
            foreach ($related as $key => $value) {

                if (!empty($value)) {
                    if (preg_match('~<td nowrap="" valign="top" class="ar fw-n borderClass">(.*)</td>~', $value, $this->matches)) {
                        $title = str_replace(":", "", $this->matches[1]);
                        $return[$title] = [];
                    } else {
                        $links = explode("</a>", $value);
                        foreach ($links as $key2 => $value2) {
                            if (preg_match('~<a href="(.*)/(.*)/(.*)">(.*)(</a>|)~', $value2, $this->matches)) {
                                $return[$title][] = [
                                    'mal_id' => (int) $this->matches[2],
                                    'type' => preg_match('~/(.*)~', $this->matches[1], $_type) ? $_type[1] : null,
                                    'url' => BASE_URL . substr($this->matches[1], 1) . '/' . $this->matches[2] . '/' . $this->matches[3],
                                    'title' => htmlspecialchars_decode(strip_tags($this->matches[4]))
                                ];
                            }
                        }
                    }
                }

            }

            $this->model->set('Anime', 'related', $return);
        });

        $this->addRule('background', '~</div>Background</h2>~', function() {
           if (!preg_match('~No background information has been added to this title.~', $this->line)) {
               if (preg_match('~</div>Background</h2>([\s\S]*)<div class="border_top~', $this->line, $this->matches)) {
                   $this->model->set('Anime', 'background', htmlspecialchars_decode(strip_tags($this->matches[1])));
               } else {
                   preg_match('~</div>Background</h2>([\s\S]*)~', $this->line, $this->matches);
                   $running = true;
                   $string = $this->matches[1];
                   $i = 1;

                   while ($running) {
                       if (preg_match('~<div class="border_top"~', $this->file[$this->lineNo + $i])) {
                           $running = false;
                       }

                       $string .= $this->file[$this->lineNo + $i];
                       $i++;
                   }

                   $string = substr($string, 0, strpos($string, '<div class="border_top"'));

                   $this->model->set('Anime', 'background', htmlspecialchars_decode(strip_tags($string)));
               }
           }
        });

        $this->addRule('opening_theme', '~<div class="theme-songs js-theme-songs opnening">([\s\S]*)</div>~', function() {
            preg_match_all('~<span class="theme-song">(.*?)</span>~', $this->matches[1], $this->matches);
            foreach ($this->matches[1] as $key => &$value) { $value = htmlspecialchars_decode($value); }
           
            $this->model->set('Anime', 'opening_theme', $this->matches[1]);
        });

        $this->addRule('ending_theme', '~<div class="theme-songs js-theme-songs ending">([\s\S]*)</div>~', function() {
            preg_match_all('~<span class="theme-song">(.*?)</span>~', $this->matches[1], $this->matches);
            foreach ($this->matches[1] as $key => &$value) { $value = htmlspecialchars_decode($value); }
            
            $this->model->set('Anime', 'ending_theme', $this->matches[1]);
        });



        /*
         * Parsing
         */

        foreach ($this->file as $lineNo => $line) {
            $this->line = $line;
            $this->lineNo = $lineNo;

            $this->find();
        }

        return (array) $this->model;

    }


}