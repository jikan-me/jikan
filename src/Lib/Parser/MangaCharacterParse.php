<?php
namespace Jikan\Lib\Parser;

use Jikan\Model\MangaCharacter as MangaCharacterModel;

class MangaCharacterParse extends TemplateParse
{

    private $return = [];

    public function parse() : Array
    {



        $this->model = new MangaCharacterModel();

        /*
         * Rules
         */


        $this->addRule('character', '~</div>Characters</h2>~', function() {
            $running = true;
            $i = 0;
            $characters = [];
            while ($running) {
                if (preg_match('~<div class="fl-r">~', $this->file[$this->lineNo + $i])) {
                    $running = false;
                }

                $character = [];

                if (preg_match('~<td valign="top" width="27" class="ac borderClass (bgColor2|bgColor1)">~', $this->file[$this->lineNo + $i])) {
                    $i += 3;
                    $image = [];
                    preg_match('~<img alt="(.*)" width="23" height="32" data-src="(.*)" data-srcset="(.*)" class="lazyload" />~', $this->file[$this->lineNo + $i], $image);
                    $character['image_url'] = trim(substr(explode(",", $image[3])[1], 0, -3));

                    $i += 5;
                    $name = [];
                    preg_match('~<a href="(.*)">(.*)</a>~', $this->file[$this->lineNo + $i], $name);
                    preg_match('~myanimelist.net/(.+)/(.*)/~', $name[1], $this->matches);
                    $character['mal_id'] = (int) $this->matches[2];
                    $character['url'] = $name[1];
                    $character['name'] = $name[2];
                    $i += 2;
                    $role = [];
                    preg_match('~<small>(.*)</small>~', $this->file[$this->lineNo + $i], $role);
                    $character['role'] = $role[1];
                    $characters[] = $character;
                }

                $i++;
            }

            $this->model->set('MangaCharacter', 'character', $characters);
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
