<?php

namespace Jikan\Lib\Parser;

use Jikan\Model\Person as PersonModel;

class PersonParse extends TemplateParse
{

    public function parse() {

        $this->model = new PersonModel;

        /*
         * Rules
         */

        $this->addRule('link_canonical', '~<link rel="canonical" href="(.*)" />~', function() {
            $this->model->set('Person', 'link_canonical', $this->matches[1]);

            preg_match('~myanimelist.net/(.+)/(.*)/~', $this->model->get('Person', 'link_canonical'), $this->matches);
            $this->model->set('Person', 'mal_id', (int) $this->matches[2]);
        });

        $this->addRule('name', '~<meta property="og:title" content="(.*?)">~', function() {

            $this->model->set('Person', 'name', $this->matches[1]);
        });

        $this->addRule('given_name', '~<div class="spaceit_pad"><span class="dark_text">Given name:</span> (.*)</div>~', function() {

            $this->model->set('Person', 'given_name', $this->matches[1]);
        });

        $this->addRule('family_name', '~<span class="dark_text">Family name:</span> (.*)<div class="spaceit_pad"><span class="dark_text">Alternate names:</span>~', function() {

            $this->model->set('Person', 'family_name', $this->matches[1]);
        });

        $this->addRule('alternate_name', '~<span class="dark_text">Alternate names:</span> (.*)</div><div class="spaceit_pad">~', function() {
            $alternateNames = $this->matches[1];
            if (strpos($alternateNames, ",")) {
                $alternateNames = explode(",", $alternateNames);
                foreach ($alternateNames as $key => &$value) {
                    $value = trim($value);
                }
            }
            $this->model->set('Person', 'alternate_name', $alternateNames);
        });

        $this->addRule('birthday', '~<span class="dark_text">Birthday:</span> (.*)</div><span class="dark_text">Website:</span>~', function() {

            $this->model->set('Person', 'birthday', $this->matches[1]);
        });

        $this->addRule('website_url', '~<span class="dark_text">Website:</span> <a href="(.*?)">(.*)</a>~', function() {

            $this->model->set('Person', 'website_url', $this->matches[1]);
        });

        $this->addRule('member_favorites', '~<div class="spaceit_pad"><span class="dark_text">Member Favorites:</span> (.*)</div>~', function() {

            $this->model->set('Person', 'member_favorites', 
                (int) str_replace(',', '', $this->matches[1])
            );
        });

        $this->addRule('more', '~<div class="people-informantion-more js-people-informantion-more">(.*)?~', function() {
            $more = str_replace("</div>", "", $this->matches[1]);
            
            $running = true;
            $i = 0;

            while ($running) {
                $i++;
                $line = $this->file[$this->lineNo + $i];
                if (strpos($line, '</td>') !== false) {
                    $running = false; break;
                }

                $more .= $line;
            }

            $this->model->set('Person', 'more', 
                htmlspecialchars_decode(strip_tags(str_replace(["<br>", "<br />"], "\\n", $more)))
            );
        });

        $this->addRule('voice_acting_role', '~</div>Voice Acting Roles</div>~', function() {
            $running = true;
            $i = 1;
            $voiceActingRoles = [];
            while ($running) {
                $line = $this->file[$this->lineNo + $i];
                if (preg_match('~</span>Anime Staff Positions</div>~', $line)) {
                    $running = false;
                }

                if (preg_match('~<tr>~', $line)) {
                    $i++;
                    $animeMeta = [];
                    preg_match('~<td valign="top" class="borderClass" width="25"><div class="picSurround"><a href="(.*)"><img data-src="(.*)" border="0" width="23" class="lazyload"></a></div></td>~', $this->file[$this->lineNo + $i], $animeMeta);
                    $i++;
                    $animeName = [];
                    preg_match('~<td valign="top" class="borderClass"><a href="((.*)/(.*)/(.*)/(.*))">(.*)</a><div class="spaceit_pad">~', $this->file[$this->lineNo + $i], $animeName);
                    $i += 2;
                    $char = [];
                    preg_match('~<td valign="top" class="borderClass" align="right" nowrap><a href="((.*)/(.*)/(.*)/(.*))">(.*)</a>&nbsp;<div class="spaceit_pad">(.*)&nbsp;</div></td>~', $this->file[$this->lineNo + $i], $char);
                    $i++;
                    $charMeta = [];
                    preg_match('~<td valign="top" class="borderClass" width="25"><div class="picSurround"><a href="(.*)"><img data-src="(.*)" border="0" alt="(.*)" width="23" class="lazyload"></a></div></td>~', $this->file[$this->lineNo + $i], $charMeta);
                    $voiceActingRoles[] = [
                        'anime' => [
                            'mal_id' => (int) $animeName[4],
                            'name' => $animeName[6],
                            'url' => $animeMeta[1],
                            'image_url' => $animeMeta[2]
                        ],
                        'character' => [
                            'mal_id' => (int) $char[4],
                            'name' => $char[6],
                            'url' => $charMeta[1],
                            'image_url' => $charMeta[2],
                            'role' => htmlspecialchars_decode($char[3])
                        ],
                    ];
                }
                $i++;
            }

            $this->model->set('Person', 'voice_acting_role', $voiceActingRoles);
        });

        $this->addRule('anime_staff_position', '~</span>Anime Staff Positions</div>~', function() {
            $running = true;
            $i = 1;
            $animeStaffPositions = [];
            while ($running) {
                $line = $this->file[$this->lineNo + $i];
                if (preg_match('~</span>Published Manga</div>~', $line)) {
                    $running = false;
                }

                if (preg_match('~<tr>~', $line)) {
                    $i++;
                    $animeMeta = [];
                    preg_match('~<td valign="top" class="borderClass" width="25"><div class="picSurround"><a href="(.*)"><img data-src="(.*)" border="0" width="23" class="lazyload"></a></div></td>~', $this->file[$this->lineNo + $i], $animeMeta);
                    $i++;
                    $animeName = [];
                    preg_match('~<td valign="top" class="borderClass"><a href="((.*)/(.*)/(.*)/(.*))">(.*)</a><div class="spaceit_pad">~', $this->file[$this->lineNo + $i], $animeName);
                    $i++;
                    $role = [];
                    preg_match('~<a href="(.*)" title="Quick add anime to my list" class="button_add">add</a> <small>(.*)</small>~', $this->file[$this->lineNo + $i], $role);
                    $animeStaffPositions[] = [
                        'anime' => [
                            'mal_id' => (int) $animeName[4],
                            'name' => $animeName[6],
                            'url' => $animeMeta[1],
                            'image_url' => $animeMeta[2]
                        ],
                        'role' => htmlspecialchars_decode($role[2])
                    ];
                }
                $i++;
            }

            $this->model->set('Person', 'anime_staff_position', $animeStaffPositions);
        });

        $this->addRule('published_manga', '~</span>Published Manga</div>~', function() {
            $running = true;
            $i = 1;
            $publsihedManga = [];
            while ($running) {
                $line = $this->file[$this->lineNo + $i];
                if (preg_match('~</table>~', $line)) {
                    $running = false;
                }

                if (preg_match('~<tr>~', $line)) {
                    $i++;
                    $mangaMeta = [];
                    preg_match('~<td valign="top" class="borderClass" width="25"><div class="picSurround"><a href="(.*)"><img data-src="(.*)" border="0" width="23" class="lazyload"></a></div></td>~', $this->file[$this->lineNo + $i], $mangaMeta);
                    $i++;
                    $mangaName = [];
                    preg_match('~<td valign="top" class="borderClass"><a href="((.*)/(.*)/(.*)/(.*))">(.*)</a><div class="spaceit_pad">~', $this->file[$this->lineNo + $i], $mangaName);
                    $i++;
                    $role = [];
                    preg_match('~<a href="(.*)" title="Quick add manga to my list" class="button_add">add</a> <small>(.*)</small>~', $this->file[$this->lineNo + $i], $role);
                    $publsihedManga[] = [
                        'manga' => [
                            'mal_id' => (int) $mangaName[4],
                            'name' => $mangaName[6],
                            'url' => $mangaMeta[1],
                            'image_url' => $mangaMeta[2]
                        ],
                        'role' => htmlspecialchars_decode($role[2])
                    ];
                }
                $i++;
            }

            $this->model->set('Person', 'published_manga', $publsihedManga);
        });

        $this->addRule('image_url', '~<td width="225" class="borderClass" style="border-width: 0 1px 0 0;" valign="top"><div style="text-align: center; style="margin-bottom: 3px;">(<a href="(.*)"><img src="(.*)" alt="(.*)"></a>|<a href="(.*)" class="btn-detail-add-picture"><i class="fa fa-plus-circle fs18 icon-plus-circle"></i><i class="fa fa-camera fs48"></i><br><span class="text">Add Picture</span></a></a>)</div>~', function() {

            $this->model->set('Person', 'image_url', $this->matches[3]);
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