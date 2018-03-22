<?php
namespace Jikan\Lib\Parser;

use Jikan\Model\AnimeEpisode as AnimeEpisodeModel;

class AnimeEpisodeParse extends TemplateParse
{

    private $return = [];

    public function parse() : Array
    {

        $this->model = new AnimeEpisodeModel;

        /*
         * Rules
         */


        $this->addRule('last_page', '~<div class="pagination ac">(.*?)</div>~', function() {
            preg_match_all('~<a class="link(|current)" href="(.*?)">(.*?)</a>~', $this->line, $this->matches);

            $this->model->set('AnimeEpisode', 'episode_last_page', ((int)substr(end($this->matches[2]), -3))/100);
        });


        $this->addRule('episode', '~<table border="0" cellspacing="0" cellpadding="0" width="100%" class="mt8 episode_list js-watch-episode-list ascend">~', function() {
            $running = true;
            $i = 1;

            while ($running) {
                $line = $this->file[$this->lineNo + $i];
                if (preg_match('~</table>~', $line)) {
                    $running = false;
                }

                if (preg_match('~<tr class="episode-list-data">~', $line)) {
                    $i++;
                    $epMeta = [];

                    preg_match('~<td class="episode-number nowrap">(.*)</td>~', $this->file[$this->lineNo + $i], $this->matches);
                    $epMeta['id'] = (int) $this->matches[1];
                    $i++;

                    preg_match('~<td class="episode-video nowrap"><a href="(.*)"><img src="(.*)" width="20" height="19" alt="(.*)"></a></td>~', $this->file[$this->lineNo + $i], $this->matches);
                    $epMeta['video_url'] = (!empty($this->matches)) ? $this->matches[1] : null;
                    $i++;

                    preg_match('~<td class="episode-title">(<span class="fl-r di-ib pr4 icon-episode-type-bg">Filler</span>|<span class="fl-r di-ib pr4 icon-episode-type-bg">Recap</span>|)<a href="(.*)" class="fl-l fw-b ">(.*)</a>~', $this->file[$this->lineNo + $i], $this->matches);
                    $epMeta['title'] = $this->matches[3];

                    $epMeta['filler'] = preg_match('~<span class="fl-r di-ib pr4 icon-episode-type-bg">Filler</span>~', $this->file[$this->lineNo + $i]) ? true : false;
                    $epMeta['recap'] = preg_match('~<span class="fl-r di-ib pr4 icon-episode-type-bg">Recap</span>~', $this->file[$this->lineNo + $i]) ? true : false;
                    $i++;

                    preg_match('~<br><span class="di-ib">(.*)&nbsp;(.*)</span>~', $this->file[$this->lineNo + $i], $this->matches);
                    $epMeta['title_japanese'] = !empty($this->matches) ? $this->matches[2] : null;
                    $epMeta['title_romanji'] = !empty($this->matches) ? $this->matches[1] : null;
                    $i += 2;

                    preg_match('~<td class="episode-aired nowrap">(.*)</td>~', $this->file[$this->lineNo + $i], $this->matches);
                    $epMeta['aired'] = $this->matches[1] != 'N/A' ? $this->matches[1] : false;
                    $i += 2;

                    preg_match('~<a href="(.*)"><i class="fa fa-comments mr4"></i>Forum</a></td>~', $this->file[$this->lineNo + $i], $this->matches);
                    $epMeta['forum_url'] = !empty($this->matches) ? $this->matches[1] : null;

                    $this->return[] = [
                        'id' => (int) $epMeta['id'],
                        'title' => htmlspecialchars_decode($epMeta['title']),
                        'title_japanese' => $epMeta['title_japanese'],
                        'title_romanji' => $epMeta['title_romanji'],
                        'aired' => $epMeta['aired'],
                        'filler' => (bool) $epMeta['filler'],
                        'recap' => (bool) $epMeta['recap'],
                        'video_url' => $epMeta['video_url'],
                        'forum_url' => $epMeta['forum_url']
                    ];
                }

                $i++;
            }

            $this->model->set('AnimeEpisode','episode', $this->return);

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
