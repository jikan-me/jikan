<?php
namespace Jikan\Lib\Parser;

use Jikan\Model\Search as SearchModel;

class SearchParse extends TemplateParse
{

    private $type;
    private $return = [];

    public function parse($type = ANIME) : Array
    {

        $this->type = $type;
        $this->model = new SearchModel();


        /*
         * Rules
         */


        switch ($type) {
            case ANIME:
            case MANGA:
                $this->addRule('result', '~<div class="js-categories-seasonal js-block-list list">~', function() {
                    $i = 1;
                    $results = [];
                    while (true) {
                        $result = [
                            'id' => null,
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
                            $result['id'] = (int) $this->matches[3];
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


                $this->addRule('result_last_page', '~<div class="spaceit" style="text-align: right;">~', function() {
                    preg_match_all('~<a href="(.*?)">(.*?)</a>~', $this->line, $this->matches);

                    $this->model->set('Search', 'result_last_page', (int) end($this->matches[2]));
                });

                break;
            case CHARACTER:
                $this->addRule('result', '~<td class="normal_header" colspan="4">Search Results</td>~', function() {
                    $i = 0;
                    $results = [];
                    while (true) {
                        $result = [
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

                        if (preg_match('~<td class="borderClass bgColor(1?|2?)" width="30"><div class="picSurround"><a href="(.*)"><img src="(.*)" border="0"></a></div>~', $line, $this->matches)) {

                            $result['url'] = $this->matches[2];
                            $result['image_url'] = $this->matches[3];

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
                                        $result['anime'][] = [
                                            'id' => (int) $value[3],
                                            'url' => $value[1],
                                            'title' => $value[5],
                                        ];
                                    }

                                }

                                if (isset($this->matches[3])) {
                                    $_anime = explode(',', $this->matches[3]);
                                    foreach ($_anime as $key => &$value) {
                                        preg_match('~<a href="(/(.*)/(.*)/(.*))">(.*)</a>~', $value, $value);
                                        $result['manga'][] = [
                                            'id' => (int) $value[3],
                                            'url' => $value[1],
                                            'title' => $value[5],
                                        ];
                                    }
                                }


                            }


                            $results[] = $result;
                        }

                        $i++;
                    }

                    $this->model->set('Search', 'result', $results);
                });


                $this->addRule('result_last_page', '~<div class="spaceit" style="text-align: right;">~', function() {
                    preg_match_all('~<a href="(.*?)">(.*?)</a>~', $this->line, $this->matches);

                    $this->model->set('Search', 'result_last_page', (int) end($this->matches[2]));
                });
                break;
            case PERSON:
               $this->addRule('result', '~<td class="normal_header" colspan="4">Search Results</td>~', function() {
                    $i = 0;
                    $results = [];
                    while (true) {
                        $result = [
                            'id' => null,
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

                            $result['id'] = (int) $this->matches[3];
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


                $this->addRule('result_last_page', '~<div class="spaceit" style="text-align: right;">~', function() {
                    preg_match_all('~<a href="(.*?)">(.*?)</a>~', $this->line, $this->matches);

                    $this->model->set('Search', 'result_last_page', (int) end($this->matches[2]));
                });
                break;
        }


        $this->loadFile($this->filePath);


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
