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
           $this->model->set('link_canonical', $this->matches[1]);
        });

        $this->addRule('title', '~<h1 class="h1"><span itemprop="name">(.*)</span></h1>~', function() {
           $this->model->set('title', $this->matches[1]);
        });

        $this->addRule('title_english', '~<span class="dark_text">English:</span> (.*)~', function() {
            $this->model->set('title_english', $this->matches[1]);
        });

        $this->addRule('title_synonyms', '~<span class="dark_text">Synonyms:</span> (.*)~', function() {
            $this->model->set('title_synonyms', $this->matches[1]);
        });

        $this->addRule('title_japanese', '~<span class="dark_text">Japanese:</span> (.*)~', function() {
            $this->model->set('title_japanese', $this->matches[1]);
        });

        $this->addRule('image_url', '~<img src="(.*)" alt="(.*)" class="ac" itemprop="image">~', function() {
            $this->model->set('image_url', $this->matches[1]);
        });

        $this->addRule('type', '~<span class="dark_text">Type:</span>~', function() {
            preg_match('~<a href="(.*)">(.*?)</a></div>~', $this->file[$this->lineNo + 1], $this->matches);
            $this->model->set('type', $this->matches[2]);
        });

        $this->addRule('episodes', '~<span class="dark_text">Episodes:</span>~', function() {
            $this->model->set('episodes', $this->file[$this->lineNo + 1]);
        });

        $this->addRule('status', '~<span class="dark_text">Status:</span>~', function() {
            $this->model->set('status', $this->file[$this->lineNo + 1]);
        });

        $this->addRule('aired', '~<span class="dark_text">Aired:</span>~', function() {
            $this->model->set('aired', $this->file[$this->lineNo + 1]);
        });

        $this->addRule('premiered', '~<span class="dark_text">Premiered:</span>~', function() {
            if (preg_match('~<a href="(.*)">(.*)</a>~', $this->file[$this->lineNo + 1], $this->matches)) {
                $this->model->set('premiered', $this->matches[2]);
            }
        });

        $this->addRule('broadcast', '~<span class="dark_text">Broadcast:</span>~', function() {
            $this->model->set('broadcast', $this->file[$this->lineNo + 1]);
        });

        $this->addRule('producer', '~<span class="dark_text">Producers:</span>~', function() {
            $return = [];
            if (!preg_match('~None found~', $this->file[$this->lineNo + 1])) {
                $array = explode(",", $this->file[$this->lineNo + 1]);

                foreach ($array as $key => $value) {
                    preg_match('~<a href="/(.*)" title="(.*)">([\s\S]*)(</a>|)~', $value, $this->matches);
                    $return[] = [
                        'url' => BASE_URL . $this->matches[1],
                        'name' => strip_tags($this->matches[2])
                    ];
                }
            }

            $this->model->set('producer', $return);
        });

        $this->addRule('licensor', '~<span class="dark_text">Licensors:</span>~', function() {
            $return = [];
            if (!preg_match('~None found~', $this->file[$this->lineNo + 1])) {
                $array = explode(",", $this->file[$this->lineNo + 1]);

                foreach ($array as $key => $value) {
                    preg_match('~<a href="/(.*)" title="(.*)">([\s\S]*)(</a>|)~', $value, $this->matches);
                    $return[] = [
                        'url' => BASE_URL . $this->matches[1],
                        'name' => strip_tags($this->matches[2])
                    ];
                }
            }

            $this->model->set('licensor', $return);
        });

        $this->addRule('studio', '~<span class="dark_text">Studios:</span>~', function() {
            $return = [];
            if (!preg_match('~None found~', $this->file[$this->lineNo + 1])) {
                $array = explode(",", $this->file[$this->lineNo + 1]);

                foreach ($array as $key => $value) {
                    preg_match('~<a href="/(.*)" title="(.*)">([\s\S]*)(</a>|)~', $value, $this->matches);
                    $return[] = [
                        'url' => BASE_URL . $this->matches[1],
                        'name' => strip_tags($this->matches[2])
                    ];
                }
            }

            $this->model->set('studio', $return);
        });

        $this->addRule('source', '~<span class="dark_text">Source:</span>~', function() {
            $this->model->set('source', $this->file[$this->lineNo + 1]);
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

            $this->model->set('genre', $return);
        });

        $this->addRule('duration', '~<span class="dark_text">Duration:</span>~', function() {
           $this->model->set('duration', $this->file[$this->lineNo + 1]);
        });

        $this->addRule('rating', '~<span class="dark_text">Rating:</span>~', function() {
           $this->model->set('rating', $this->file[$this->lineNo + 1]);
        });

        $this->addRule('score', '~<span class="dark_text">Score:</span>~', function(){
            preg_match('~<span(.*?)>(.*)</span><sup>1</sup> \(scored by <span(.*?)>(.*)</span> users\)~', $this->file[$this->lineNo + 1], $this->matches);

            $this->model->set('score', (float) $this->matches[2]);
            $this->model->set('scored_by', (int) str_replace(",", "", $this->matches[4]));
        });

        $this->addRule('rank', '~<span class="dark_text">Ranked:</span>~', function() {
           if (!preg_match('~N/A<sup>2</sup>~', $this->file[$this->lineNo + 1], $this->matches)) {
               preg_match('~#(.*)<sup>2</sup>~', $this->file[$this->lineNo + 1], $this->matches);

               $this->model->set('rank', (int) $this->matches[1]);
           }
        });

        $this->addRule('popularity', '~<span class="dark_text">Popularity:</span>~', function() {
           preg_match('~#(.*)~', $this->file[$this->lineNo + 1], $this->matches);

           $this->model->set('popularity', (int) $this->matches[1]);
        });

        $this->addRule('members', '~<span class="dark_text">Members:</span>~', function() {
           $this->model->set('members', (int) str_replace(",", "", $this->file[$this->lineNo + 1]));
        });

        $this->addRule('favorites', '~<span class="dark_text">Favorites:</span>~', function() {
            $this->model->set('favorites', (int) str_replace(",", "", $this->file[$this->lineNo + 1]));
        });

        $this->addRule('synopsis', '~<meta property="og:description" content="(.*)">~', function() {
            $this->model->set('synopsis', $this->matches[1]);
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
                            if (preg_match('~<a href="(.*)">(.*)(</a>|)~', $value2, $this->matches)) {
                                $return[$title][] = [
                                    'url' => $this->matches[1],
                                    'title' => strip_tags($this->matches[2])
                                ];
                            }
                        }
                    }
                }

            }

            $this->model->set('related', $return);
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