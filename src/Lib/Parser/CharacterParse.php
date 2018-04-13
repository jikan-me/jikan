<?php

namespace Jikan\Lib\Parser;

use Jikan\Model\Character as CharacterModel;

class CharacterParse extends TemplateParse
{

    public function parse() {

        $this->model = new CharacterModel;

        /*
         * Rules
         */

        $this->addRule('link_canonical', '~<link rel="canonical" href="(.*)" />~', function() {
           $this->model->set('Character', 'link_canonical', $this->matches[1]);

            preg_match('~myanimelist.net/(.+)/(.*)/~', $this->model->get('Character', 'link_canonical'), $this->matches);
            $this->model->set('Character', 'mal_id', (int) $this->matches[2]);
        });

        $this->addRule('name', '~<div class="normal_header" style="height: 15px;">(.*) (<span style="font-weight: normal;"><small>(.*)</small></span>|)</div>~', function() {

            $this->model->set('Character', 'name', trim($this->matches[1]));
            if (!empty($this->matches[2])) {
                $this->model->set('Character', 'name_kanji', $this->matches[3]);
            }
        });

        $this->addRule('nicknames', '~<div id="contentWrapper"><div><div class="header-right">~', function() {
            preg_match('~<h1 class="h1">(.*)</h1>~', $this->file[$this->lineNo+1], $this->matches);

            $this->model->set('Character', 'nicknames', trim($this->matches[1]));
        });

        $this->addRule('about', '~<div class="normal_header" style="height: 15px;">(.*) (<span style="font-weight: normal;"><small>(.*)</small></span>|)</div>(.*?)<br />~', function() {
            $match = [];
            $match[] = $this->matches[4];

            $finding = true;
            $i = $this->lineNo;
            while ($finding) {
                if (preg_match('~<div class="normal_header">Voice Actors</div>~', $this->file[$i])) {
                    $finding = false;
                } else {
                    $i++;
                    $match[] = $this->file[$i];
                }
            }

            //filter out the residue
            $about = implode(' ', $match);
            $about = str_replace('<br />', "\n", $about);
            $about = str_replace('<div class="normal_header">Voice Actors</div>', '', $about);
            $about = str_replace('<div class="spoiler"><input type="button" class="button show_button" onClick="this.nextSibling.style.display=\'inline-block\';this.style.display=\'none\';" data-showname="Show spoiler" data-hidename="Hide spoiler" value="Show spoiler"><span class="spoiler_content" style="display:none"><input type="button" class="button hide_button" onClick="this.parentNode.style.display=\'none\';this.parentNode.parentNode.childNodes[0].style.display=\'inline-block\';" value="Hide spoiler">', '', $about);
            $about = str_replace('<br>', '', $about);
            $about = str_replace('</span></div>', '', $about);
            $about = htmlspecialchars_decode($about);
            
            $this->model->set('Character', 'about', $about);
        });

        $this->addRule('animeography', '~<div class="normal_header">Animeography</div>~', function() {
            $running = true;
            $i = 1;
            $animeography = [];
            while ($running) {
                $line = $this->file[$this->lineNo + $i];
                if (preg_match('~</table>~', $line)) {
                    $running = false;
                }

                if (preg_match('~<tr>~', $line)) {
                    $i++;
                    $this->matches = [];
                    preg_match('~<td width="25" class="borderClass" valign="top"><div class="picSurround"><a href="(.*)"><img src="(.*)" border="0"></a></div></td>~', $this->file[$this->lineNo + $i], $animeMeta);
                    $i++;
                    $animeName = [];
                    preg_match('~<td valign="top" class="borderClass"><a href="((.*)/(.*)/(.*)/(.*))">(.*)</a>~', $this->file[$this->lineNo + $i], $animeName);

                    $animeography[] = [
                        'mal_id' => (int) $animeName[4],
                        'name' => $animeName[6],
                        'url' => $animeMeta[1],
                        'image_url' => $animeMeta[2]
                    ];
                }
                $i++;
            }
            
            $this->model->set('Character', 'animeography', $animeography);
        });

        $this->addRule('mangaography', '~<div class="normal_header">Mangaography</div>~', function() {
            $running = true;
            $i = 1;
            $mangaography = [];
            while ($running) {
                $line = $this->file[$this->lineNo + $i];
                if (preg_match('~</table>~', $line)) {
                    $running = false;
                }

                if (preg_match('~<tr>~', $line)) {
                    $i++;
                    $mangaMeta = array();
                    preg_match('~<td width="25" class="borderClass" valign="top"><div class="picSurround"><a href="(.*)"><img src="(.*)" border="0"></a></div></td>~', $this->file[$this->lineNo + $i], $mangaMeta);
                    $i++;
                    $mangaName = array();
                    preg_match('~<td valign="top" class="borderClass"><a href="((.*)/(.*)/(.*)/(.*))">(.*)</a>~', $this->file[$this->lineNo + $i], $mangaName);

                    $mangaography[] = [
                        'mal_id' => (int) $mangaName[4],
                        'name' => $mangaName[6],
                        'url' => $mangaMeta[1],
                        'image_url' => $mangaMeta[2]
                    ];
                }
                $i++;
            }

            $this->model->set('Character', 'mangaography', $mangaography);
        });

        $this->addRule('voice_actors', '~<div class="normal_header">Voice Actors</div>~', function() {
            $running = true;
            $i = 1;
            $voiceActors = [];
            while ($running) {
                $line = $this->file[$this->lineNo + $i]; // bugs
                if (
                    preg_match('~<h2><div class="floatRightHeader">~', $line) ||
                    preg_match('~<div class="mauto clearfix pt24" style="width:760px;">~', $line) ||
                    preg_match('~<div class="mauto clearfix pt24" style="width:760px;">~', $this->file[$this->lineNo])
                    ) {
                    $running = false;
                }

                if (preg_match('~<tr>~', $line)) {
                    $i++;
                    $personMeta = [];
                    preg_match('~<td class="borderClass" valign="top" width="25"><div class="picSurround"><a href="(.*)"><img src="(.*)" border="0"></a></div></td>~', $this->file[$this->lineNo + $i], $personMeta);
                    $i++;
                    $personName = [];
                    preg_match('~<td class="borderClass" valign="top"><a href="((.*)/(.*)/(.*)/(.*))">(.*)</a>~', $this->file[$this->lineNo + $i], $personName);
                    $i++;
                    $personType = [];
                    preg_match('~<div style="margin-top: 2px;"><small>(.*)</small></div>~', $this->file[$this->lineNo + $i], $personType);

                    $voiceActors[] = [
                        'mal_id' => (int) $personName[4],
                        'name' => $personName[6],
                        'url' => $personMeta[1],
                        'image_url' => $personMeta[2],
                        'language' => $personType[1]
                    ];
                }
                $i++;
            }

            $this->model->set('Character', 'voice_actor', $voiceActors);
        });

        $this->addRule('member_favorites', '~Member Favorites: (.*)~', function() {

            $this->model->set('Character', 'member_favorites', 
                (int) str_replace(',', '', trim($this->matches[1]))
            );
        });

        $this->addRule('image_url', '~<td width="225" class="borderClass" style="border-width: 0 1px 0 0;" valign="top"><div style="text-align: center;">(<a href="(.*)"><img src="(.*)" alt="(.*)"></a>|<a href="(.*)" class="btn-detail-add-picture"><i class="fa fa-plus-circle fs18 icon-plus-circle"></i><i class="fa fa-camera fs48"></i><br><span class="text">Add Picture</span></a></a>)</div>~', function() {

            $this->model->set('Character', 'image_url', $this->matches[3]);
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