<?php

namespace Jikan\Lib\Parser;

use Jikan\Model\MangaStats as MangaStats;

class MangaStatsParse extends TemplateParse
{

    public function parse() {

        $this->model = new MangaStats;

        /*
         * Rules
         */


        $this->addRule('reading', '~<div class="spaceit_pad"><span class="dark_text">Reading:</span> (.*)</div>~', function() {
            $this->model->set('MangaStats', 'reading', 
                (int) str_replace(',', '', $this->matches[1])
            );
        });

        $this->addRule('completed', '~<div class="spaceit_pad"><span class="dark_text">Completed:</span> (.*)</div>~', function() {
            $this->model->set('MangaStats', 'completed', 
                (int) str_replace(',', '', $this->matches[1])
            );
        });

        $this->addRule('on_hold', '~<div class="spaceit_pad"><span class="dark_text">On-Hold:</span> (.*)</div>~', function() {
            $this->model->set('MangaStats', 'on_hold', 
                (int) str_replace(',', '', $this->matches[1])
            );
        });

        $this->addRule('dropped', '~<div class="spaceit_pad"><span class="dark_text">Dropped:</span> (.*)</div>~', function() {
            $this->model->set('MangaStats', 'dropped', 
                (int) str_replace(',', '', $this->matches[1])
            );
        });

        $this->addRule('plan_to_watch', '~<div class="spaceit_pad"><span class="dark_text">Plan to Read:</span> (.*)</div>~', function() {
            $this->model->set('MangaStats', 'plan_to_read', 
                (int) str_replace(',', '', $this->matches[1])
            );
        });

        $this->addRule('total', '~<div class="spaceit_pad"><span class="dark_text">Total:</span> (.*)</div>~', function() {
            $this->model->set('MangaStats', 'total', 
                (int) str_replace(',', '', $this->matches[1])
            );
        });


        $this->addRule('score_stats', '~<h2>Score Stats</h2>~', function() {
            $score = [
                1 => ['percentage' => null, 'votes' => null],
                2 => ['percentage' => null, 'votes' => null],
                3 => ['percentage' => null, 'votes' => null],
                4 => ['percentage' => null, 'votes' => null],
                5 => ['percentage' => null, 'votes' => null],
                6 => ['percentage' => null, 'votes' => null],
                7 => ['percentage' => null, 'votes' => null],
                8 => ['percentage' => null, 'votes' => null],
                9 => ['percentage' => null, 'votes' => null],
                10 => ['percentage' => null, 'votes' => null]
            ];


            $i = 0;
            while (true) {
                $line = $this->file[$this->lineNo + $i];
                if (preg_match('~<h2><span style="(.*)">All Members</span>Recently Updated By</h2>~', $line)) {
                    break;
                }


                if (preg_match('~<td width="20">(.*?)</td>~', $line, $this->matches)) {
                    $_score = (int) $this->matches[1];
                    $i++;
                    preg_match('~<td><div class="spaceit_pad"><div class="updatesBar" style="(.*?)"></div><span>&nbsp;(.*?)% <small>\((.*?) votes\)</small></span></div></td>~', $this->file[$this->lineNo + $i], $this->matches);

                    $score[ $_score ] = [
                        'percentage' => (float) $this->matches[2],
                        'votes' => (int) $this->matches[3]
                    ];

                }

                $i++;
            }



            $this->model->set('MangaStats', 'score_stats', $score);
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