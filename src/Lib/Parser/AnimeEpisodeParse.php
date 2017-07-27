<?php
namespace Jikan\Lib\Parser;

use Jikan\Model\Anime as AnimeModel;

class AnimeEpisodeParse extends TemplateParse
{

    private $return = [];
    private $page;

    public function parse()
    {
        $this->model = new AnimeModel;

        /*
         * Rules
         */


        $this->page = $this->filePath;
        echo $this->page;

        while (!is_null($this->page)) {

            $this->loadFile($this->page);

            var_dump($this->file);

            $this->page = null;
            foreach ($this->file as $lineNo => $line) {
                if (preg_match('~<link rel="next" href="(.*)" />~', $line, $pagePath)) {
                    $this->page = $pagePath[1];
                    break;
                }
            }

            echo "READING " . $this->page . "<br>";

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
                        $epMeta['title'] = $this->matches[2];

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
                            'title' => $epMeta['title'],
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

            });

        }



        /*
         * Parsing
         */

        foreach ($this->file as $lineNo => $line) {
            $this->line = $line;
            $this->lineNo = $lineNo;

            $this->find();
        }

        var_dump($this->return);

        $this->model->set('episode', $this->return);

        return $this->model;
    }
}
