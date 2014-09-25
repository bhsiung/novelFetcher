<?php
namespace Api\Remote;
class Book extends Base
{
	function get($f3,$params)
	{
		$this->setSiteClassByName($params['siteName']);
		$url = call_user_func_array($this->siteClass.'::infoUrl', array($params['bookId']));
		$this->response = call_user_func_array($this->siteClass.'::fetchBookInfo',array($url));
		return $this->response;
	}
}
