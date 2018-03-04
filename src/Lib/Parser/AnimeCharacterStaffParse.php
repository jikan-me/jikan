<?php

//refactor $match = []; to $this->match as TemplateParse provides that option now

namespace Jikan\Lib\Parser;

use Jikan\Model\AnimeCharacterStaff as AnimeCharacterStaffModel;

class AnimeCharacterStaffParse extends TemplateParse
{

    private $return = [];

    public function parse() : Array
    {



        $this->model = new AnimeCharacterStaffModel();

        /*
         * Rules
         */

        $this->addRule('character', '~</div>Characters & Voice Actors</h2>~', function() {
            $running = true;
            $i = 0;
            $characters = [];
            while ($running) {
                if (preg_match('~<a name="staff"></a>~', $this->file[$this->lineNo + $i])) {
                    $running = false;
                }

                $character = [];

                if (preg_match('~<td valign="top" width="27" class="ac borderClass (bgColor2|bgColor1)">~', $this->file[$this->lineNo + $i])) {
                    $i += 3;
                    $image = array();
                    preg_match('~<img alt="(.*)" width="23" height="32" data-src="(.*)" data-srcset="(.*)" class="lazyload" />~', $this->file[$this->lineNo + $i], $image);
                    $character['image_url'] = trim(substr(explode(",", $image[3])[1], 0, -3));

                    $i += 5;
                    $name = array();
                    preg_match('~<a href="(.*)">(.*)</a>~', $this->file[$this->lineNo + $i], $name);
                    preg_match('~myanimelist.net/(.+)/(.*)/~', $name[1], $this->matches);
                    $character['mal_id'] = (int) $this->matches[2];
                    $character['url'] = $name[1];
                    $character['name'] = $name[2];

                    $i += 2;
                    $role = array();
                    preg_match('~<small>(.*)</small>~', $this->file[$this->lineNo + $i], $role);
                    $character['role'] = $role[1];



                    $running2 = true;
                    $character['voice_actor'] = array();
                    while ($running2) {
                        if (preg_match('~</table>~', $this->file[$this->lineNo + $i])) {
                            $running2 = false;
                        }

                        if (preg_match('~<td valign="top" align="right" style="padding: 0 4px;" nowrap="">~', $this->file[$this->lineNo + $i])) {
                            $i++;
                            $name = array();
                            preg_match('~<a href="(.*)">(.*)</a>~', $this->file[$this->lineNo + $i], $name);
                            $i++;
                            $role = array();
                            preg_match('~<small>(.*)</small>~', $this->file[$this->lineNo + $i], $role);
                            $i += 5;
                            $image = array();
                            preg_match('~<img alt="(.*)" width="23" height="32" data-src="(.*)" data-srcset="(.*)" class="lazyload" />~', $this->file[$this->lineNo + $i], $image);

                            preg_match('~myanimelist.net/(.+)/(.*)/~', $name[1], $this->matches);

                            $character['voice_actor'][] = array(
                                'mal_id' => (int) $this->matches[2],
                                'name' => $name[2],
                                'url' => $name[1],
                                'language' => $role[1],
                                'image_url' => trim(substr(explode(",", $image[3])[1], 0, -3))
                            );
                        }

                        $i++;
                    }
                    $characters[] = $character;
                }

                $i++;
            }

            $this->model->set('AnimeCharacterStaff', 'character', $characters);
        });


        $this->addRule('staff', '~<a name="staff"></a>~', function() {
            $running = true;
            $i = 0;
            $staff = array();
            while ($running) {
                $person = array();
                if (preg_match('~<div class="fl-l">~', $this->file[$this->lineNo + $i])) {
                    $running = false;
                }

                if (preg_match('~<table border="0" cellpadding="0" cellspacing="0" width="100%">~', $this->file[$this->lineNo + $i])) {
                    $i += 5;
                    $match = array();
                    preg_match('~<img alt="(.*)" width="23" height="32" data-src="(.*)" data-srcset="(.*)" class="lazyload" />~', $this->file[$this->lineNo + $i], $match);
                    $person['image_url'] = trim(substr(explode(",", $match[3])[1], 0, -3));
                    $i += 5;
                    $match = array();
                    preg_match('~<a href="(.*)">(.*)</a>~', $this->file[$this->lineNo + $i], $match);
                    preg_match('~myanimelist.net/(.+)/(.*)/~', $match[1], $this->matches);
                    $person['mal_id'] = (int) $this->matches[2];
                    $person['url'] = $match[1];
                    $person['name'] = $match[2];
                    $i += 2;
                    $match = array();
                    preg_match('~<small>(.*)</small>~', $this->file[$this->lineNo + $i], $match);
                    $person['role'] = $match[1];

                    $staff[] = $person;
                }

                $i++;
            }

            $this->model->set('AnimeCharacterStaff', 'staff', $staff);
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
