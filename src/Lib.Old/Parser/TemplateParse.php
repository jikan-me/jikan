<?php

namespace Jikan\Lib\Parser;

use Jikan\Helper\Utils as Util;

/**
 * Class TemplateParse
 *
 * @package Jikan\Lib\Parser
 */
abstract class TemplateParse
{

    public $status;
    public $file;
    public $filePath;
    public $data;
    public $model;

    public $rules = [];
    public $matches = [];
    public $line;
    public $lineNo;

    /**
     * @param $filePath
     */
    public function setPath($filePath)
    {
        $this->filePath = $filePath;
    }

    public function loadFile()
    {
        if (!is_null($this->filePath)) {
            if (Util::isURL($this->filePath)) {
                $this->setStatus(Util::getStatus($this->filePath));
                if (!Util::existsURL($this->status)) {
                    http_response_code($this->status);

                    if ($this->status == 429) {
                        throw new \Exception('MyAnimeList Rate Limit reached');
                    }

                    throw new \Exception('File does not exist');
                }
            } else {
                if (!file_exists($this->filePath)) {
                    throw new \Exception('File does not exist');
                }
            }

            $this->file = @file($this->filePath);

            if (!is_bool($this->file)) {
                array_walk($this->file, Util::class.'::trim'); // bystanders begone!
            } else {
                throw new \Exception('MyAnimeList Rate Limit reached');
            }
        } else {
            throw new \Exception('File path is null');
        }
    }

    /**
     * @param $status
     */
    public function setStatus($status)
    {
        $this->status = (int)$status;
    }

    public function find()
    {
        foreach ($this->rules as $index => $value) {
            if (!$value['found'] && preg_match($value['regex'], $this->line, $this->matches)) {
                if ($value['merge']) {
                    $this->rules[$index] = array_merge(
                        $this->rules[$index],
                        ($value['args'] !== false) ? $value['callback'](...$value['args']) : $value['callback']()
                    );
                } else {
                    $this->rules[$index] = ($value['args'] !== false) ? $value['callback'](
                        ...$value['args']
                    ) : $value['callback']();
                }

                $this->rules[$index]['found'] = true;
            }
        }
    }

    /**
     * @param String   $index
     * @param String   $regex
     * @param callable $callback
     * @param null     $args
     * @param bool     $merge
     */
    public function addRule(String $index, String $regex, callable $callback, $args = null, Bool $merge = false)
    {
        $args = $args ?? false; // The reason you need PHP 7.1+
        $this->rules[$index] = [
            'regex'    => $regex,
            'callback' => $callback,
            'args'     => $args,
            'merge'    => $merge,
            'found'    => false,
        ];
    }
}
