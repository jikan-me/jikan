<?php
namespace Jikan\Lib\Parser;

use Jikan\Model\Seasonal as SeasonalModel;

class SeasonalParse extends TemplateParse
{

    private $return = [];

    public function parse() : Array
    {

        $this->model = new SeasonalModel();


        /*
         * Rules
         */

        $this->addRule('seasonal', '~<div class="js-categories-seasonal">~', function() {

            $i = 1;
            $seasonal = [];

            while (true) {
                if (preg_match('~<div class="mauto clearfix pt24"~', $this->file[$this->lineNo + $i])) {
                    break;
                }

                if (preg_match('~<div class="seasonal-anime (.*?)"~', $this->file[$this->lineNo + $i], $this->matches)) {

                    $anime = [
                        'mal_id' => null,
                        'url' => null,
                        'title' => null,
                        'image_url' => null,
                        'synopsis' => null,
                        'producer' => [],
                        'licensor' => [],
                        'episodes' => null,
                        'source' => null,
                        'genre' => [],
                        'airing_start' => null,
                        'score' => null,
                        'members' => null,
                        'r18_plus' => false,
                    ];

                    $anime['r18_plus'] = strpos($this->matches[1], 'r18') ? true : false;

                    $i += 3;
                    preg_match('~<a href="(https://myanimelist.net/anime/(.*)/(.*))" class="link-title">(.*)</a>~', $this->file[$this->lineNo + $i], $this->matches);


                    $anime['mal_id'] = (int) $this->matches[2];
                    $anime['url'] = $this->matches[1];
                    $anime['title'] = $this->matches[4];

                    $i += 5;
                    preg_match('~<span class="producer">(.*)</span>~', $this->file[$this->lineNo + $i], $this->matches);
                    $producers = explode("</a>", $this->matches[1]);
                    foreach ($producers as $key => $value) {
                        if (preg_match('~<a href="/(.*)" title="(.*)">([\s\S]*)(</a>|)~', $value)) {
                            preg_match('~<a href="/(.*)" title="(.*)">([\s\S]*)(</a>|)~', $value, $this->matches);
                            $anime['producer'][] = [
                                'url' => BASE_URL . $this->matches[1],
                                'name' => strip_tags($this->matches[2])
                            ];
                        }
                    }

                    $i += 2;
                    preg_match('~<a href="(.*)"><span>(.*) ep(s|)</span>~', $this->file[$this->lineNo +$i], $this->matches);
                    $anime['episodes'] = $this->matches[2] == '?' ? null : (int) $this->matches[2];

                    $i += 4;
                    preg_match('~<span class="source">(.*)</span>~', $this->file[$this->lineNo + $i], $this->matches);
                    $anime['source'] = $this->matches[1];

                    $i += 5;
                    if (preg_match('~<div class="genres js-genre"~', $this->file[$this->lineNo + $i])) {
                        while (true) {
                            if (preg_match('~<div class="image">~', $this->file[$this->lineNo + $i])) { break; }
                            if (preg_match('~<a href="/(.*)" title="(.*)">(.*)</a>~', $this->file[$this->lineNo + $i], $this->matches)) {
                                $anime['genre'][] = [
                                    'url' => BASE_URL . $this->matches[1],
                                    'name' => $this->matches[3]
                                ];
                            }
                            $i++;
                        }
                    }

                    if (preg_match('~<div class="image"><img width="(.*?) height="(.*?)" alt="(.*?) data-src="(.*?)" data-srcset="(.*?)" class="lazyload" />~', $this->file[$this->lineNo + $i], $this->matches) ||
                        preg_match('~<img src="(.*?)" width="(.*?)" height="(.*?)" alt="(.*?)" srcset="(.*?)" />~', $this->file[$this->lineNo + $i], $this->matches)
                    ) {
                        $anime['image_url'] = trim(substr(explode(",", $this->matches[5])[1], 0, -3));
                    }

                    while(!strpos($this->file[$this->lineNo + $i], '<div class="synopsis js-synopsis">')){$i++;} // haxing MAL's inconsistencies

                    echo htmlentities($this->file[$this->lineNo + $i]) . "<br><br>";

                    $seasonal[] = $anime;
                }

                $i++;
            }


            $this->model->set('Seasonal', 'season', $seasonal);
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
