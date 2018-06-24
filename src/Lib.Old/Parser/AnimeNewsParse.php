<?php
namespace Jikan\Lib\Parser;

use Jikan\Model\AnimeNews as AnimeNewsModel;

class AnimeNewsParse extends TemplateParse
{

    private $return = [];

    public function parse() : Array
    {



        $this->model = new AnimeNewsModel;

        /*
         * Rules
         */


        $this->addRule('news', '~<h2 class="mb8">News</h2>~', function() {
            $running = true;
            $i = 0;

            $news = [];
            while ($running) {
                $line = $this->file[$this->lineNo + $i];
                if (preg_match('~</table>~', $line)) {
                    $running = false;
                }

                if (preg_match('~<div class="clearfix">(<div class="picSurround fl-l mr8 ml3 mt4"><a href="/(.*)" class="image-link"><img src="(.*)" data-src="(.*)" data-srcset="(.*)" alt="(.*)" width="50" height="78" class="lazyload"></a></div>|)<p class="spaceit"><a href="/(.*)"><strong>(.*)</strong></a></p>~', $line, $this->matches)) {

                    $tmp = [
                        'url' => BASE_URL . $this->matches[6],
                        'image_url' => !empty($this->matches[5]) ? trim(substr(explode(",", $this->matches[5])[1], 0, -3)) : null,
                        'forum_url' => null,
                        'comments' => 0,
                        'title' => $this->matches[8],
                        'date' => null,
                        'author_url' => null,
                        'author' => null
                    ];
                    if (preg_match('~<p class="lightLink spaceit">(.*) by <a href="/(.*)">(.*)</a> \| <a href="/(.*)">Discuss \((.*) comments\)</a></p></div>~', $this->file[$this->lineNo + $i + 2], $this->matches)) {

                        $tmp['date'] = trim($this->matches[1]);
                        $tmp['author_url'] = BASE_URL . $this->matches[2];
                        $tmp['author'] = $this->matches[3];
                        $tmp['forum_url'] = BASE_URL . $this->matches[4];
                        $tmp['comments'] = (int) $this->matches[5];
                    }

                    $news[] = $tmp;
                }

                $this->model->set('AnimeNews', 'news', $news);

                $i++;
            }
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
