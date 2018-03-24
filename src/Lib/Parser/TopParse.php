<?php
namespace Jikan\Lib\Parser;

use Jikan\Model\Top as TopModel;

class TopParse extends TemplateParse
{

    private $return = [];

    public function parse($type) : Array
    {

        $this->model = new TopModel();

        /*
         * Rules
         */
        $this->addRule('top', '~<h1 class="h1">(.*?)</h1>~', function($type) {
            $top = [];
            $i = 0;

            while (true) {
                if (preg_match('~<div class="mauto clearfix pt24~', $this->file[$this->lineNo + $i])) {
                    break;
                }

                if (preg_match('~<tr class="ranking-list">~', $this->file[$this->lineNo + $i])) {

                    $item = [
                        'mal_id' => null,
                        'rank' => null,
                        'url' => null,
                        'image_url' => null,
                        'title' => null,
                        'type' => null,
                        'score' => null,
                        'members' => null,
                    ];

                    if ($type == ANIME) {
                        $item['airing_start'] = null;
                        $item['airing_end'] = null;
                        $item['episodes'] = 0;
                    }
                    if ($type == MANGA) {
                        $item['publishing_start'] = null;
                        $item['publishing_end'] = null;
                        $item['volumes'] = 0;
                    }

                    $i+=2;
                    if (preg_match('~<span class="lightLink top-anime-rank-text rank([0-9]{1,})">(.*?)</span>~', $this->file[$this->lineNo + $i], $this->matches)) {
                        $item['rank'] = (int) $this->matches[2];
                    }

                    $i+=3;
                    if (preg_match('~<a class="hoverinfo_trigger fl-l ml12 mr8" href="(https://myanimelist.net/(.*)/(.*)/(.*))" id="(.*?)" rel="(.*?)">~', $this->file[$this->lineNo + $i], $this->matches)) {
                        $item['url'] = $this->matches[1];
                        $item['mal_id'] = (int) $this->matches[3];
                    }

                    $i++;
                    if (preg_match('~<img width="50" height="70" alt="(.*?)" class="lazyload" border="0" data-src="(.*?)" />~', $this->file[$this->lineNo + $i], $this->matches)) {
                        $item['image_url'] = trim(substr(explode(",", $this->matches[2])[1], 0, -3));
                    }


                    if ($type == ANIME) {$i+=6;}
                    if ($type == MANGA) {$i+=7;}
                    if (preg_match('~<a class="hoverinfo_trigger(.*?)" href="(.*?)" id="(.*?)" rel="(.*?)">(.*?)</a>~', $this->file[$this->lineNo + $i], $this->matches)) {
                        $item['title'] = $this->matches[5];
                    }

                    if ($type == ANIME) {
                        $i+=1;
                        if (preg_match('~(.*) \((.*) eps\)<br>~', $this->file[$this->lineNo + $i], $this->matches)) {
                            $item['type'] = $this->matches[1];
                            $item['episodes'] = (int) $this->matches[2];
                        }
                    }
                    if ($type == MANGA) {
                        $i+=2;
                        if (preg_match('~(.*) \((.*) vols\)<br>~', $this->file[$this->lineNo + $i], $this->matches)) {
                            $item['type'] = $this->matches[1];
                            $item['volumes'] = (int) $this->matches[2];
                        }
                    }

                    $i++;
                    if (preg_match('~(.*) - (.*)<br>~', $this->file[$this->lineNo + $i], $this->matches)) {
                        if ($type == ANIME) {
                            $item['airing_start'] = !empty(trim($this->matches[1])) ? $this->matches[1] : null;
                            $item['airing_end'] = !empty(trim($this->matches[2])) ? $this->matches[2] : null;
                        }
                        if ($type == MANGA) {
                            $item['publishing_start'] = !empty(trim($this->matches[1])) ? $this->matches[1] : null;
                            $item['publishing_end'] = !empty(trim($this->matches[2])) ? $this->matches[2] : null;
                        }
                    }

                    $i++;
                    if (preg_match('~(.*) (members|favorites)~', $this->file[$this->lineNo + $i], $this->matches)) {
                        $item['members'] = (int) str_replace(',', '', $this->matches[1]);
                    }

                    if ($type == ANIME) {$i+=4;}
                    if ($type == MANGA) {$i+=6;}
                    if (preg_match('~<i class="icon-score-star mr4 on"></i><span class="text on">(.*)</span>~', $this->file[$this->lineNo + $i], $this->matches)) {
                        $item['score'] = (float) $this->matches[1];
                    }

                    $top[] = $item;

                }

                $i++;
            }

            $this->model->set('Top', 'top', $top);
        }, [$type]);


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
