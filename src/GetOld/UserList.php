<?php
// prep update
// will implement once MAL is 100% back up
namespace Jikan\Get;

use Jikan\Lib\Parser\UserListParse;

/**
 * Class UserList
 *
 * @package Jikan\Get
 */
class UserList extends Get
{

    const VALID_TYPES = [ANIME, MANGA];
    const VALID_STATUS = [
        USER_LIST_ALL,
        USER_LIST_WATCHING,
        USER_LIST_COMPLETED,
        USER_LIST_ONHOLD,
        USER_LIST_DROPPED,
        USER_LIST_PLAN_TO_WATCH,
        USER_LIST_PTW,
    ];

    /**
     * UserList constructor.
     *
     * @param        $username
     * @param        $type
     * @param string $status
     *
     * @throws \Exception
     */
    public function __construct($username, $type, $status = USER_LIST_ALL)
    {

        if (!in_array($type, self::VALID_TYPES)) {
            throw new \Exception('Unsupported type. Refer to src/config.php for valid constants');
        }

        if (!is_null($subtype) && !in_array($subtype, self::VALID_SUBTYPES)) {
            throw new \Exception('Unsupported subtype. Refer to src/config.php for valid constants');
        }

        $this->parser = new UserListParse;

        $link = BASE_URL.$type.'list/'.$username;


        // $this->parser->setPath($link);

        // $this->parser->loadFile();

        // $this->response['code'] = $this->parser->status;
        // $this->response = array_merge($this->response, $this->parser->parse($type));
    }
}
