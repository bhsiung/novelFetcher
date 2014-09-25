<?php
class RemoteBookParser
{
	const SUPPORTTED_DOMAIN_PATERN = '/^https?:\/\/[a-z]+\.kxwxw\.com/';

	static function prepare($url)
	{
		if(!preg_match(self::SUPPORTTED_DOMAIN_PATERN,$url)){
			throw new Exception("url of $url is not supportted");
		}
		// todo remove hard code path
		require_once('/var/www/lib/simpleHtmlDom/simple_html_dom.php');
	}
	static function fetchArticle($db,$title,$bookId,$url)
	{/*{{{*/
		self::prepare($url);
		if(!preg_match('/kxwxw\.com\/read\/ds_\d+_\d+\.html/',$url)){
			throw new Exception("url of $url is not supportted article");
		}
		error_log("start fetching article from $url");
		$article = new \DB\SQL\Mapper($db,'bookChapters');
		if($article->load(array('url=?',$url))){
			error_log('found, skip id:'.$article->id. ', url = '.$article->url);
		}else{
			error_log('not found , grabbing');
			$article->url = $url;
			$article->bookId = $bookId;
			$article->title = $title;
			$html = file_get_html($url);
			foreach($html->find('div[class=rda] div[class=rdaa]') as $element){
				$article->text = $element->innerText();
			}
			$article->save();

		}
	}/*}}}*/
	static function fetchBookinfo($siteClass,$url)
	{/*{{{*/
		$response = [];
		error_log("start fetching $url");
		self::prepare($url);
		if(!call_user_func_array("$siteClass::isValidInfoUrl",array($url))){
			throw new Exception("url of $url is not supportted book info");
		}
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
		$chaptersUrl = preg_replace('/info\/ds_(\d+).html/','Chapter/ds_\1.html',$url);
		error_log("start fetching chapters from $chaptersUrl");

		//$chapters = \RemoteBookParser::fetchBookChapters($db,$chaptersUrl);
		/*
		foreach($chapters as $chapterTitle => $chapterUrl){
			self::fetchArticle($db,$chapterTitle,$book->id,$chapterUrl);
		}
		$author = new Author($db);
		if(!$author->load(array('name=?',$authorName))){
			$author->name = $authorName;
			$author->save();
		}
		$book = new Book($db);
		if(!$book->load(array('name=?',$bookName))){
			$book->name = $bookName;
			$book->authorId = $author->id;
			$book->save();
		}*/
	}/*}}}*/
	static function fetchBookChapters($db,$url)
	{/*{{{*/
		self::prepare($url);
		$chapters = [];
		if(!preg_match('/kxwxw\.com\/Chapter\/ds_\d+\.html/',$url)){
			throw new Exception("url of $url is not supportted chapter list");
		}
		$urlParts = parse_url($url);
		$urlPrefix = $urlParts['scheme'].'://'.$urlParts['host'];
		$html = file_get_html($url);

		foreach($html->find('div[class=chdb] ul li a') as $element){
			$chapterTitle = $element->text();
			$href = $element->href;
			if(preg_match('/^http:/',$href)){
				$chapterUrl = $href;
			}else{
				if(preg_match('/^\//',$href)){
					$chapterUrl = $urlPrefix.$element->href;
				}else{
					$chapterUrl = $urlPrefix.preg_replace('/[^\/]+$/',$element->href,$urlParts['path']);
				}
			}
			$chapters[$chapterTitle] = $chapterUrl;
		} 
		return $chapters;
	}/*}}}*/
}
