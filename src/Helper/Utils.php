<?php

namespace Jikan\Helper\Utils;


class Utils
{
	static public function validURL($url) {
		// return (filter_var($this->filePath, FILTER_VALIDATE_URL) ? true : false);
		return preg_match('`^http(s)?://`', $url) ? true : false;
	}

	static public function existsURL($url) {
		return (substr(get_headers($url)[0], 9, 3) == "200") ? true : false;
	}
}