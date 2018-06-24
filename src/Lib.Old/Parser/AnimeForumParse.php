<?php
namespace Jikan\Lib\Parser;

use Jikan\Model\AnimeForum as AnimeForumModel;

class AnimeForumParse extends TemplateParse
{

    private $return = [];

    public function parse() : Array
    {



        $this->model = new AnimeForumModel;

        /*
         * Rules
         */

        $this->addRule('topic', '~<table border="0" cellpadding="0" cellspacing="0" width="100%" id="forumTopics">~', function() {
            $i = 0;
            $topics = [];

            while (true) {
                $line = $this->file[$this->lineNo + $i];

                if (preg_match('~</table~', $line)) {
                    break;
                }


                if (preg_match('~<tr id="topicRow([0-9]{1,})">~', $line)) {
                    $i += 2;

                    $topic = [
                        'topic_id' => null,
                        'url' => null,
                        'title' => null,
                        'date_posted' => null,
                        'author_name' => null,
                        'author_url' => null,
                        'replies' => 0,
                        'last_post' => [
                            'url' => null,
                            'author_name' => null,
                            'author_url' => null,
                            'date_relative' => null,
                        ]
                    ];

                    preg_match('~<td class="forum_boardrow([0-9]{1,})" style="border-width: 0px 1px 1px 0px;">(Poll: |) <a href="/(.*)">(.*)</a> <small>(.*?)</small><br><span class="forum_postusername"><a href="/(.*)">(.*)</a></span> - <span class="lightLink">(.*)</span></td>~', $this->file[$this->lineNo + $i], $this->matches);


                    $topic['url'] = BASE_URL . $this->matches[3];
                    $topic['title'] = $this->matches[4];
                    $topic['author_name'] = $this->matches[7];
                    $topic['author_url'] = BASE_URL . $this->matches[6];
                    $topic['date_posted'] = @date_format(date_create($this->matches[8]), 'o-m-d');

                    preg_match('~forum/\?topicid=(.*)~', $this->matches[3], $this->matches);
                    $topic['topic_id'] = (int) $this->matches[1];

                    $i++;
                    preg_match('~<td align="center" width="75" class="forum_boardrow([0-9]{1,})" style="border-width: 0px 1px 1px 0px;">(.*)</td>~', $this->file[$this->lineNo+$i], $this->matches);
                    $topic['replies'] = (int) $this->matches[2];

                    $i++;
                    preg_match('~<td align="right" width="130" class="forum_boardrow([0-9]{1,})" style="border-width: 0px 1px 1px 0px;" nowrap>by <a href="/(.*)">(.*)</a> <a href="/(.*)" title="Go to the Last Post">&raquo;&raquo;</a><br>(.*)</td>~', $this->file[$this->lineNo+$i], $this->matches);
                    $topic['last_post']['author_url'] = BASE_URL . $this->matches[2];
                    $topic['last_post']['author_name'] = BASE_URL . $this->matches[3];
                    $topic['last_post']['url'] = BASE_URL . $this->matches[4];
                    $topic['last_post']['date_relative'] = $this->matches[5];

                    $topics[] = $topic;
                }

                $i++;
            }


            $this->model->set('AnimeForum', 'topic', $topics);
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
