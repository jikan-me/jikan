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
            case ANIME || MANGA: 

                $this->addRule('result', '~<div class="js-categories-seasonal js-block-list list">~', function() {
                    $i = 1;
                    $results = [];
                    while (true) {
                        $result = [
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

                            preg_match('~<a class="hoverinfo_trigger" href="(.*)" id="(.*)" rel="(.*)">~', $this->file[$this->lineNo + $i], $this->matches);
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
                break;
            case PERSON:
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
