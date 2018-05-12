<?php
namespace Jikan\Lib\Parser;

use Jikan\Model\Search as SearchModel;

class SearchParse extends TemplateParse
{

    private $type;
    private $return = [];

    public function parse($type = ANIME, $status) : Array
    {

        $this->type = $type;
        $this->model = new SearchModel();


        /*
         * Rules
         */

        switch ($type) {
            case ANIME:
            case MANGA:

                $this->addRule('title', '~<meta property="og:type" content="(books.book|video.tv_show)">~', function() {
                    $results = [];
                    $result = [
                        'mal_id' => null,
                        'url' => null,
                        'image_url' => null,
                        'title' => null,
                        'description' => null,
                        'type' => null,
                        'score' => null
                    ];

                    $i = 0;
                    while(true) {
                        $line = $this->file[$this->lineNo + $i];
                        if (preg_match('~<div class="clearfix mauto mt16"~', $line)) {
                            break;
                        }

                        if (preg_match('~<meta property="og:title" content="(.*?)">~', $line, $this->matches)) {
                            $result['title'] = $this->matches[1];
                        }

                        if (preg_match('~<meta property="og:image" content="(.*?)">~', $line, $this->matches)) {
                            $result['image_url'] = $this->matches[1];
                        }

                        if (preg_match('~<meta property="og:url" content="(.*?)">~', $line, $this->matches)) {
                            $result['url'] = $this->matches[1];
                            preg_match('~https://myanimelist.net/(anime|manga)/(.*)/(.*)~', $this->matches[1], $this->matches);
                            $result['mal_id'] = (int) $this->matches[2];
                        }

                        if (preg_match('~<meta property="og:description" content="(.*?)">~', $line, $this->matches)) {
                            $result['description'] = $this->matches[1];
                        }

                        if (preg_match('~<span class="dark_text">Type:</span>~', $line)) {
                            preg_match('~(<a href="(.*)">(.*?)</a>|(.*))</div>~', $this->file[$this->lineNo + $i + 1], $this->matches);
                            $result['type'] = (empty($this->matches[3]) ? $this->matches[4] : $this->matches[3]);
                        }

                        if (preg_match('~<span class="dark_text">Score:</span>~', $line)) {
                            preg_match('~<span(.*?)>(.*)</span><sup>1</sup> \(scored by <span(.*?)>(.*)</span> users\)~', $this->file[$this->lineNo + $i + 1], $this->matches);
                            $result['score'] = (float) $this->matches[2];
                        }

                        $i++;
                    }

                    $results[] = $result;
                    $this->model->set('Search', 'result', $results);
                });

                $this->addRule('result', '~<div class="js-categories-seasonal js-block-list list">~', function() {
                    $i = 1;
                    $results = [];
                    while (true) {
                        $result = [
                            'mal_id' => null,
                            'url' => null,
                            'image_url' => null,
                            'title' => null,
                            'description' => null,
                            'type' => null,
                            'score' => null
                        ];
                        $line = $this->file[$this->lineNo + $i];

                        if (preg_match('~</table>~', $line)) {
                            break;
                        }

                        if (preg_match('~^<td class="borderClass bgColor(0?|1?)" valign="top" width="50">$~', $line)) {
                            $i += 2;

                            preg_match('~<a class="hoverinfo_trigger" href="((.*)/(.*)/(.*))" id="(.*)" rel="(.*)">~', $this->file[$this->lineNo + $i], $this->matches);
                            $result['mal_id'] = (int) $this->matches[3];
                            $result['url'] = $this->matches[1];

                            $i++;

                            preg_match('~<img width="50" height="70" alt="(.*)" border="0" data-src="(.*)" data-srcset="(.*)" class="lazyload" />~', $this->file[$this->lineNo + $i], $this->matches);
                            $result['image_url'] = trim(substr(explode(",", $this->matches[3])[1], 0, -3));
                            $result['title'] = $this->matches[1];


                            ($this->type == ANIME ? $i += 8 : $i += 10);

                            if (preg_match('~<div class="pt4">(.*?)(.?|<a href=".{1,}">read more.</a>)</div>~', $this->file[$this->lineNo + $i], $this->matches)) {
                                $result['description'] = htmlspecialchars_decode($this->matches[1]);
                            }

                            $i += 2;
                            $result['type'] = trim($this->file[$this->lineNo + $i]);
                            $i += 2;
                            $result[($this->type == ANIME ? 'episodes' : 'volumes')] = (int) trim($this->file[$this->lineNo + $i]);
                            $i += 2;
                            $result['score'] = (float) trim($this->file[$this->lineNo + $i]);
                            $i += 2;
                            $result['members'] = (int) str_replace(',', '', trim($this->file[$this->lineNo + $i]));

                            $results[] = $result;
                        }

                        $i++;
                    }

                    $this->model->set('Search', 'result', $results);
                });

                break;
            case CHARACTER:
                $this->addRule('result', '~<td class="normal_header" colspan="4">Search Results</td>~', function() {
                    $i = 0;
                    $results = [];
                    while (true) {
                        $result = [
                            'mal_id' => null,
                            'url' => null,
                            'image_url' => null,
                            'name' => null,
                            'nicknames' => null,
                            'anime' => [],
                            'manga' => []
                        ];
                        $line = $this->file[$this->lineNo + $i];

                        if (preg_match('~</table>~', $line)) {
                            break;
                        }

                        if (preg_match('~<td class="borderClass bgColor(1?|2?)" width="30"><div class="picSurround"><a href="((.*)/(.*)/(.*))"><img src="(.*)" border="0"></a></div>~', $line, $this->matches)) {

                            $result['mal_id'] = (int) $this->matches[4];
                            $result['url'] = $this->matches[2];
                            $result['image_url'] = $this->matches[6];

                            $i += 3;

                            preg_match('~<a href="(.*)">(.*?)</a>(<br /><small>(.*)</small>|)~', $this->file[$this->lineNo + $i], $this->matches);
                            $result['name'] = $this->matches[2];
                            (isset($this->matches[4]) && !empty($this->matches[4])) ?
                                $result['nicknames'] = str_replace(['(', ')'], '', trim($this->matches[4]))
                            : $result['nicknames'] = null ;

                            $i += 2;
                            // :uh: i give up finding a pattern here, let's just do it the nasty way
                            if (
                                preg_match('~<td class="borderClass bgColor(1?|2?)"><small> Anime: (.*)<div>Manga: (.*)</div></small></td>~', $this->file[$this->lineNo + $i], $this->matches)
                            ||  preg_match('~<td class="borderClass bgColor(1?|2?)"><small> Anime: (.*)</small></td>~', $this->file[$this->lineNo + $i], $this->matches)
                            ||  preg_match('~<td class="borderClass bgColor(1?|2?)"><small> <div>Manga: (.*)</div></small></td>~', $this->file[$this->lineNo + $i], $this->matches)
                                ) 
                            {


                                if (isset($this->matches[2])) {
                                    $_anime = explode(',', $this->matches[2]);
                                    foreach ($_anime as $key => &$value) {
                                        preg_match('~<a href="(/(.*)/(.*)/(.*))">(.*)</a>~', $value, $value);
                                        if (!empty($value[5])) {
                                            if ($value[2] == 'anime' || $value[2] == 'manga') {
                                                $result[$value[2]][] = [
                                                    'mal_id' => (int) $value[3],
                                                    'url' => BASE_URL . substr($value[1], 1),
                                                    'title' => htmlspecialchars_decode($value[5])
                                                ];
                                            }
                                        }
                                    }
                                }

                                if (isset($this->matches[3])) {
                                    $_anime = explode(',', $this->matches[3]);
                                    foreach ($_anime as $key => &$value) {
                                        preg_match('~<a href="(/(.*)/(.*)/(.*))">(.*)</a>~', $value, $value);
                                        if (!empty($value[5])) {
                                            if ($value[2] == 'anime' || $value[2] == 'manga') {
                                                $result[$value[2]][] = [
                                                    'mal_id' => (int) $value[3],
                                                    'url' => BASE_URL . substr($value[1], 1),
                                                    'title' => htmlspecialchars_decode($value[5])
                                                ];
                                            }
                                        }
                                    }
                                }


                            }


                            $results[] = $result;
                        }

                        $i++;
                    }

                    $this->model->set('Search', 'result', $results);
                });

                break;
            case PERSON:

                $this->addRule('title', '~<meta property="og:type" content="article">~', function() {
                    $results = [];
                    $result = [
                        'mal_id' => null,
                        'url' => null,
                        'image_url' => null,
                        'name' => null,
                        'nicknames' => null,
                    ];

                    $i = 0;
                    while(true) {
                        $line = $this->file[$this->lineNo + $i];
                        if (preg_match('~<div class="mauto clearfix pt24"~', $line)) {
                            break;
                        }

                        if (preg_match('~<meta property="og:title" content="(.*?)">~', $line, $this->matches)) {
                            $result['name'] = trim($this->matches[1]);
                        }

                        if (preg_match('~<meta property="og:image" content="(.*?)">~', $line, $this->matches)) {
                            $result['image_url'] = $this->matches[1];
                        }

                        if (preg_match('~<meta property="og:url" content="(.*?)">~', $line, $this->matches)) {
                            $result['url'] = $this->matches[1];
                            preg_match('~https://myanimelist.net/people/(.*)/(.*)~', $this->matches[1], $this->matches);
                            $result['mal_id'] = (int) $this->matches[1];
                        }


                        $i++;
                    }

                    $results[] = $result;
                    $this->model->set('Search', 'result', $results);
                });

               $this->addRule('result', '~<td class="normal_header" colspan="4">Search Results</td>~', function() {
                    $i = 0;
                    $results = [];
                    while (true) {
                        $result = [
                            'mal_id' => null,
                            'url' => null,
                            'image_url' => null,
                            'name' => null,
                            'nicknames' => null,
                        ];
                        $line = $this->file[$this->lineNo + $i];

                        if (preg_match('~</table>~', $line)) {
                            break;
                        }

                        if (preg_match('~<td class="borderClass" width="25"><div class="picSurround"><a href="(/(.*)/(.*)/(.*))"><img src="(.*)"></a></div></td>~', $line, $this->matches)) {

                            $result['mal_id'] = (int) $this->matches[3];
                            $result['url'] = BASE_URL . $this->matches[1];
                            $result['image_url'] = $this->matches[5];

                            $i++;
                            preg_match('~<td class="borderClass"><a href="(.*)">(.*)</a>(<br><small>(.*)</small></td>|)~', $this->file[$this->lineNo + $i], $this->matches);
                            $result['name'] = $this->matches[2];
                            (isset($this->matches[4]) && !empty($this->matches[4])) ?
                                $result['nicknames'] = str_replace(['(', ')'], '', trim($this->matches[4]))
                            : $result['nicknames'] = null ;

                            $results[] = $result;
                        }

                        $i++;
                    }

                    $this->model->set('Search', 'result', $results);
                });

                break;
        }


        $this->addRule('result_last_page', '~<div class="spaceit" style="text-align: right;">~', function() {
            preg_match_all('~<a href="(.*?)">(.*?)</a>~', $this->line, $this->matches);

            if (!empty($this->matches[2])) {
                $this->model->set('Search', 'result_last_page', (int) end($this->matches[2]));
            }
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
