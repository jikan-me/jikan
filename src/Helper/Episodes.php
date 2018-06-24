<?php

namespace Jikan\Helper;

class Episodes extends \Jikan\Abstracts\Helper
{
	public function build() {
		$query = "";
		foreach ($this->container as $key => $value) {
			$query .= $key . "=" . $value . "&";
		}

		return $query;
	}
}