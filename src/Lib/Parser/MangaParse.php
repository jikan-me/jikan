<?php

namespace Jikan\Lib\Parser;

use Jikan\Model\Manga as MangaModel;

class MangaParse extends TemplateParse
{

    public function parse() {

        $this->model = new MangaModel;

        /*
         * Rules
         */

        $this->addRule('link_canonical', '~<link rel="canonical" href="(.*)" />~', function() {
            $this->model->set('Manga', 'link_canonical', $this->matches[1]);

            preg_match('~myanimelist.net/(.+)/(.*)/~', $this->model->get('Manga', 'link_canonical'), $this->matches);
            $this->model->set('Manga', 'mal_id', (int) $this->matches[2]);
        });

        $this->addRule('title', '~<h1 class="h1"><span itemprop="name">(.*)</span></h1>~', function(){
            $this->model->set('Manga', 'title', htmlspecialchars_decode($this->matches[1]));
        });

        $this->addRule('title_english', '~<span class="dark_text">English:</span> (.*?)</div>~', function(){
            $this->model->set('Manga', 'title_english', htmlspecialchars_decode($this->matches[1]));
        });

        $this->addRule('title_synonyms', '~<span class="dark_text">Synonyms:</span> (.*)</div><div class="spaceit_pad"><span class="dark_text">Japanese:</span>~', function(){
            $this->model->set('Manga', 'title_synonyms', htmlspecialchars_decode($this->matches[1]));
        });

        $this->addRule('title_japanese', '~<span class="dark_text">Japanese:</span>(.*?)</div>~', function(){
            $this->model->set('Manga', 'title_japanese', htmlspecialchars_decode(trim($this->matches[1])));
        });

        $this->addRule('status', '~<div class="spaceit"><span class="dark_text">Status:</span> (.*)</div>~', function(){
            $this->model->set('Manga', 'status', $this->matches[1]);

            if (strpos($this->model->get('Manga', 'status'), "Publishing") !== false)
                $this->model->set('Manga', 'publishing', true);
        });

        $this->addRule('image_url', '~<img src="(.*)" alt="(.*)" itemprop="image" class="ac">~', function(){
            $this->model->set('Manga', 'image_url', $this->matches[1]);
        });

        $this->addRule('type', '~<span class="dark_text">Type:</span> <a href="(.*)">(.*?)</a></div>~', function(){
            $this->model->set('Manga', 'type', $this->matches[2]);
        });

        $this->addRule('volumes', '~<span class="dark_text">Volumes:</span> (.*)$~', function(){
           $this->model->set('Manga', 'volumes',
               ($this->matches[1] != 'Unknown') ? (int) trim($this->matches[1]) : $this->matches[1]
           );
        });

        $this->addRule('chapters', '~<span class="dark_text">Chapters:</span> (.*)$~', function(){
            $this->model->set('Manga', 'chapters',
                ($this->matches[1] != 'Unknown') ? (int) trim($this->matches[1]) : $this->matches[1]
            );
        });

        $this->addRule('published', '~<span class="dark_text">Published:</span>(.*)</div>~', function(){
            $this->model->set('Manga', 'published_string', trim($this->matches[1]));

            if (!empty($this->model->get('Manga', 'published_string')) && $this->model->get('Manga', 'published_string') != 'Not available') {
                if (strpos($this->model->get('Manga', 'published_string'), 'to')) {
                    preg_match('~(.*) to (.*)~', $this->model->get('Manga', 'published_string'), $this->matches);
                    $this->model->set('Manga', 'published', [
                        'from' => (strpos($this->matches[1], '?') !== false) ? null : @date_format(date_create($this->matches[1]), 'o-m-d'),
                        'to' => (strpos($this->matches[2], '?') !== false) ? null : @date_format(date_create($this->matches[2]), 'o-m-d')
                    ]);
                } else {
                    if (preg_match('~^[0-9]{4}$~', $this->model->get('Manga', 'published_string'))) {
                        $this->model->set('Manga', 'published_string', [
                            'from' => null,
                            'to' => null
                        ]);                            
                    } else {
                        $this->model->set('Manga', 'published', [
                            'from' => (strpos($this->model->get('Manga', 'published_string'), '?') !== false) ? null : @date_format(date_create($this->model->get('Manga', 'published_string')), 'o-m-d'),
                            'to' => (strpos($this->model->get('Manga', 'published_string'), '?') !== false) ? null : @date_format(date_create($this->model->get('Manga', 'published_string')), 'o-m-d')
                        ]);
                    }
                }
            } else {
                $this->model->set('Manga', 'published', [
                    'from' => null,
                    'to' => null
                ]);
            }
        });

        $this->addRule('rank', '~<span class=\"dark_text\">Ranked:<\/span> #(.*[[:alnum:]])<sup>~', function(){
            $this->model->set('Manga', 'rank',
                ($this->matches[1] == "N/A" ? -1 : (int) $this->matches[1])
            );
        });

        $this->addRule('score', '~<span class="dark_text">Score:</span> <span itemprop="ratingValue">(.*)</span><sup><small>1</small></sup> <small>\(scored by <span itemprop="ratingCount">(.*)</span> users\)</small>~', function(){

            if (is_null($this->model->get('Manga', 'score'))) {
                $this->model->set('Manga', 'score', (float) $this->matches[1]);
                $this->model->set('Manga', 'scored_by', (int) str_replace(",", "", $this->matches[2]));
            }
        });

        $this->addRule('score2', '~<div class="po-r js-statistics-info di-ib" data-id="info1"><span class="dark_text">Score:</span> (.*)<sup><small>1</small></sup> <small>\(scored by (.*) users\)</small>~', function() {

            if (is_null($this->model->get('Manga', 'score'))) {
                $this->model->set('Manga', 'score', (float) $this->matches[1]);
                $this->model->set('Manga', 'scored_by', (int) str_replace(",", "", $this->matches[2]));
            }
        });


        $this->addRule('popularity', '~<span class=\"dark_text\">Popularity:<\/span> #(.*[[:alnum:]])<\/div>~', function(){
            $this->model->set('Manga', 'popularity',
                ($this->matches[1] == "N/A" ? -1 : (int) $this->matches[1])
            );
        });

        $this->addRule('members', '~<span class="dark_text">Members:</span>(.*)</div>~', function(){
            $this->model->set('Manga', 'members',
                (int) str_replace(",", "", trim($this->matches[1]))
            );
        });

        $this->addRule('favorites', '~<div><span class="dark_text">Favorites:</span> (.*)</div>~', function(){
            $this->model->set('Manga', 'favorites',
                (int) str_replace(",", "", trim($this->matches[1]))
            );
        });

        $this->addRule('synopsis', '~<meta property=\"og:description\" content=\"(.*)\">~', function(){
            $this->model->set('Manga', 'synopsis',
                htmlspecialchars_decode(strip_tags($this->matches[1]))
            );
        });

        $this->addRule('background', '~</div>Background</h2>~', function(){
            if (!preg_match('~No background information has been added to this title.~', $this->line)) {
                if (preg_match('~</div>Background</h2>([\s\S]*)<div class="border_top~', $this->line, $this->matches)) {
                    $this->model->set('Manga', 'background', htmlspecialchars_decode(strip_tags($this->matches[1])));
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
                    $string = substr($string, 0, strpos($string, '<div class="border_top'));
                    $this->model->set('Manga', 'background', htmlspecialchars_decode(strip_tags($string)));
                }
            }
        });

        $this->addRule('related', '~<table class="anime_detail_related_anime"~', function(){
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
                            if (preg_match('~<a href="/(.*)/(.*)/(.*)">(.*)(</a>|)~', $value2, $this->matches)) {
                                $return[$title][] = [
                                    'mal_id' => (int) $this->matches[2],
                                    'type' => $this->matches[1],
                                    'url' => BASE_URL . $this->matches[1] . '/' . $this->matches[2] . '/' . $this->matches[3],
                                    'title' => strip_tags($this->matches[4])
                                ];
                            }
                        }
                    }
                }

            }

            $this->model->set('Manga', 'related', $return);
        });

        $this->addRule('genre', '~<span class="dark_text">Genres:</span>~', function() {
            $return = [];
            if (!preg_match('~No genres have been added yet~', $this->file[$this->lineNo + 1])) {
                $array = explode(",", $this->file[$this->lineNo + 1]);

                foreach ($array as $key => $value) {
                    preg_match('~<a href="/(.*)" title="(.*)">(.*)</a>~', $value, $this->matches);
                    $return[] = [
                        'url' => BASE_URL . $this->matches[1],
                        'name' => strip_tags($this->matches[2])
                    ];
                }
            }

            $this->model->set('Manga', 'genre', $return);
        });

        $this->addRule('author', '~<span class="dark_text">Authors:</span>~', function(){
            $return = [];
            if (!preg_match("~None~", $this->file[$this->lineNo + 1])) {
                $_authors = array();
                preg_match('`^(.*?)</div>`', trim($this->file[$this->lineNo + 1]), $_authors);

                $authors = explode("),", $_authors[1]);

                foreach ($authors as $key => $value) {
                    if (!strpos($value, ')')) {$value .= ')'; }
                    $break = array();
                    preg_match('`<a href=\"/(.*)\">(.*)</a>`', $value, $break);
                    $return[] = array(
                        'url' => BASE_URL . $break[1],
                        'name' => strip_tags($break[2])
                    );
                }
            }

            $this->model->set('Manga', 'author', $return);
        });

        $this->addRule('serialization', '~<span class="dark_text">Serialization:</span>~', function(){
            $return = [];
            if (!preg_match('~None~', $this->file[$this->lineNo + 1])) {
                $array = explode("</a>", $this->file[$this->lineNo + 1]);


                foreach ($array as $key => $value) {
                    //preg_match('~<a href="/(.*)" title="(.*)">([\s\S]*)(</a>|)~', $value, $this->matches);
                    preg_match('~<a href="/(.*)" title="(.*)">(.*)(</a>|)~', $value, $this->matches);

                    if (!empty($this->matches)) {
                        $return[] = [
                            'url' => BASE_URL . $this->matches[1],
                            'name' => strip_tags($this->matches[2])
                        ];
                    }
                }
            }

            $this->model->set('Manga', 'serialization', $return);
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