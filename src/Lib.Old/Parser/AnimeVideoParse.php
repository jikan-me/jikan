<?php
namespace Jikan\Lib\Parser;

use Jikan\Model\AnimeVideos as AnimeVideoModel;

class AnimeVideoParse extends TemplateParse
{

    private $return = [];

    public function parse() : Array
    {



        $this->model = new AnimeVideoModel;

        /*
         * Rules
         */



        $this->addRule('episode', '~<h2 class="mt0 pt0 clearfix">~', function() {
            $parsingLine = $this->file[$this->lineNo + 10];
            preg_match_all('~<div class="video-list-outer">(.*?)</div>~', $parsingLine, $this->matches);

            $episode = [];
            if (!empty($this->matches)) {
                foreach ($this->matches[1] as $key => $value) {
                    preg_match('~<a class="video-list di-ib po-r" href="(.*)"><img class="thumbs lazyload" src="(.*)" data-src="(.*)" width="320" height="320" data-pin-no-hover="true" data-title="(.*)" data-video-id="(.*)" data-anime-id="(.*)"><div class="info-container clearfix"><span class="title">(.*)<span class=" ml4"></span><br><span class="episode-title" title="(.*)">(.*)</span></span>~', $value, $this->matches);

                    $episode[] = [
                        'url' => $this->matches[1],
                        'image_url' => $this->matches[3],
                        'episode' => $this->matches[7],
                        'title' => $this->matches[8]
                    ];
                }
            }

            $this->model->set('AnimeVideos', 'episode', $episode);
        });

        $this->addRule('promo', '~<div class="video-block promotional-video mt16">~', function() {
            $running = true;
            $i = 1;

            $promo = [];
            while($running) {
                $line = $this->file[$this->lineNo + $i];
                if (preg_match('~</table>~', $line)) {
                    $running = false;
                }

                if (preg_match('~<div class="video-list-outer po-r pv"><a class="iframe js-fancybox-video video-list di-ib po-r" href="(.*)"><img class="thumbs lazyload" src="(.*)" data-src="(.*)" width="320" height="320" data-pin-no-hover="true" data-title="(.*)" data-video-id="(.*)" data-anime-id="(.*)"><div class="info-container"><span class="title">(.*)</span></div>(<span class="btn-play" style="background-color: rgba\(255, 255, 255, 0\);">play</span>|)</a>~', $line, $this->matches)) {

                    $promo[] = [
                        'video_url' => $this->matches[1],
                        'image_url' => $this->matches[3],
                        'title' => $this->matches[7]
                    ];

                }

                $i++;
            }

            $this->model->set('AnimeVideos', 'promo', $promo);
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
