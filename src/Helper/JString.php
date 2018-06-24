<?php

namespace Jikan\Helper;

class JString 
{

	public static function cleanse(string $string) : string {
		return trim(
			htmlspecialchars_decode(
				strip_tags($string), 
			ENT_QUOTES)
		);
	}

	public static function utf8(string $string) : string {
		return utf8_encode($string);
	}

	public static function assertNull(string $string) {
		return empty($string) ? null : $string;
	}

}