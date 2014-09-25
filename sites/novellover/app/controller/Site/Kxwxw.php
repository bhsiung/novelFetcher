<?php
namespace Site;
require_once('/var/www/lib/simpleHtmlDom/simple_html_dom.php');
class Kxwxw extends \Site
{
	const NAME ='kxwxw';
	static function bookIdFromUrl($url)
	{
		if(preg_match('/^https?:\/\/[a-z]{1,3}.kxwxw.com\/info\/[a-z0-9]+_(?<bookId>\d+).html$/',$url,$matches)){
			return $matches['bookId'];
		}
	}
	static function infoUrl($bookId)
	{
		$urlParts = ['http://'];
		$urlParts[] = 'tw.kxwxw.com';
		$urlParts[] = "/info/any_$bookId.html";
		return implode('',$urlParts);
	}
	static function chapterListUrl($bookId)
	{
		$urlParts = ['http://'];
		$urlParts[] = 'tw.kxwxw.com';
		$urlParts[] = "/Chapter/any_$bookId.html";
		return implode('',$urlParts);
	}
	static function chapterUrl($bookId,$chapterId=null)
	{
		$urlParts = ['http://'];
		$urlParts[] = 'tw.kxwxw.com';
		$urlParts[] = "/read/any_{$bookId}_$chapterId.html";
		return implode('',$urlParts);
	}
	static function fetchBookinfo($url)
	{
		$response = [];
		error_log("start fetching $url");
		$html = file_get_html($url);
		$response['tags'] = []; 
		foreach($html->find('div[class=deatab1] div[class=deata2] h3 a') as $element){
			$response['bookName'] = $element->innerText();
		}
		foreach($html->find('div[class=deatab1] div[class=deatab2] ul li a') as $element){
			$response['authorName'] = $element->innerText();
		}
		foreach($html->find('div[class=deaab1] div[class=deaab13] a') as $element){
			$response['tags'][] = $element->innerText();
		}
		if(!isset($response['bookName']) || !$response['authorName']){
			throw new Exception('fail to extract sufficient info to save to db');
		}
		return $response;
	}
	static function fetchChapters($url)
	{/*{{{*/
		$chapters = [];
		$html = file_get_html($url);

		foreach($html->find('div[class=chdb] ul li a') as $element){
			$chapterData = [];
			$chapterData['title'] = $element->text();
			$chapterData['url'] = self::absoluteHref($url,$element->href);;
			if(preg_match('/[a-z]+_\d+_(?P<id>\d+).html$/',$chapterData['url'],$matches)){
				$id = $matches['id'];
				$chapterData['id'] = $id;
				$chapters[$id] = $chapterData;
			}
		} 
		return $chapters;
	}/*}}}*/
	static function fetchArticle($url)
	{/*{{{*/
		error_log("start fetching article from $url");
		$article = [];
		//ds_67633_427757
		if(preg_match('/ds_(?P<bookId>\d+)_(?P<chapterId>\d+).html$/',$url,$matches)){
			$article['id'] = $matches['chapterId'];
			$article['bookId'] = $matches['bookId'];
		}
		$html = file_get_html($url);
		foreach($html->find('h1') as $element){
			$article['title'] = $element->innerText();
			$article['title'] = preg_replace('/ /','',$article['title']);
		}
		foreach($html->find('div[class=rda] div[class=rdaa]') as $element){
			$article['text'] = $element->innerText();
			self::filterArticle($article['title'],$article['text']);
		}
		return $article;
	}/*}}}*/
}
