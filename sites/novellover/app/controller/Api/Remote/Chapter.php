<?php
namespace Api\Remote;
/*
 * @usage:
 * http://www.novellover.net/remote/sites/kxwxw/books/67633/chapters/427756?options={%22preview%22:true}
 * http://www.novellover.net/remote/sites/kxwxw/books/67633/chapters/427756?options={%22includeInfo%22:true}
 * http://www.novellover.net/remote/sites/kxwxw/books/67633/chapters/427756
 * http://www.novellover.net/remote/sites/kxwxw/books/67633/chapters
 */
class Chapter extends Base
{
	protected $siteClass;
	function get($f3,$params)
	{
		$this->setSiteClassByName($params['siteName']);
		$url = call_user_func_array($this->siteClass.'::chapterUrl', array(
			$params['bookId'],
			$params['chapterId']
		));
		$this->response = call_user_func_array($this->siteClass.'::fetchArticle',array($url));
		$this->response['bookId'] = $params['bookId'];
		$this->response['chapterId'] = $params['chapterId'];
		$preview = $this->options('preview');
		if($preview){
			echo "<h1>{$this->response['title']}</h1><div>{$this->response['text']}</div>";
			return;
		}else if($this->options('includeInfo')){
			$this->includeBookInfo($f3,$params);
		}
		return $this->response;
	}
	function index($f3,$params)
	{
		$this->setSiteClassByName($params['siteName']);
		$url = call_user_func_array($this->siteClass.'::chapterListUrl', array($params['bookId']));
		$this->response = call_user_func_array($this->siteClass.'::fetchChapters',array($url));
		return $this->response;
	}
	function afterRoute($f3,$params)
	{
		parent::afterRoute($f3,$params);
	}
	protected function includeBookInfo($f3,$params)
	{
		$book = new Book();
		$bookInfo = $book->get($f3,$params);
		$this->response = array_merge($bookInfo,$this->response);
	}
}
