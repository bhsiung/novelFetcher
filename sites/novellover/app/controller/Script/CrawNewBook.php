<?php
/*
 * usage: php /var/www/sites/novellover/index.php "/cli/CrawNewBook/Kxwxw/88920/"
 * usage: php /var/www/sites/novellover/index.php "/cli/CrawNewBook/Kxwxw/88920/package"
 * Usage: php /var/www/sites/novellover/index.php "/cli/CrawNewBook/kxwxw/70581"
 */
namespace Script;
class CrawNewBook extends \Script
{
	protected $f3;
	protected $packageMode = false;
	const TMP_PATH = '/tmp/';
	function __construct($f3,$optionString)
	{
		$client = new \Elasticsearch\Client();
		error_log("start crawing");
		$this->f3 = $f3;
		$files = [];
		$index = 0;
		$parts = explode('/',$optionString);
		list($siteName,$bookId) = $parts;
		$this->packageMode = true;
		$book = $this->loadRemoteBook($bookId,$siteName);
		$chapters = $this->loadRemoteChapters($bookId,$siteName);
		error_log("got chapter length of ".count($chapters));
		$lastChapterId = $this->lastChapterId($bookId);
		$total = count($chapters);
		$count = 0;
		foreach($chapters as $chapterId => $chapter){
			if($lastChapterId && $chapterId<=$lastChapterId){
				continue;
			}
			$chapterData = (array)$this->loadRemoteChapter($chapterId,$bookId,$siteName);
			//$files[] = $this->saveToFile($index,$chapterData);
			//if($count++>2){ break; }
			$params = [
				'id' => "$bookId:$chapterId",
				'index' => 'chapter',
				'type' => 'book',
				'body' => $chapterData
			];
			$ret = $client->index($params);
			$index++;
			error_log("loading chapter $index / $total");
		}
		if($this->packageMode){
			//$this->zipFile($bookId,$files,$chapterData);
		}
	}
	function lastChapterId($bookId)
	{
		try{
			$client = new \Elasticsearch\Client();
			$searchParams = [];
			$searchParams['index'] = 'chapter';
			$searchParams['type']  = 'book';
			$searchParams['fields']  = ['chapterId'];
			$searchParams['body']['query']['match']['bookId'] = $bookId;
			$searchParams['body']['sort'] = ['chapterId'=>['order'=>'desc']];
			$searchParams['size'] = 1;
			$retDoc = $client->search($searchParams);
			if(!isset($retDoc['hits']['hits'][0])){
				return;
			}
			$chapterData = $retDoc['hits']['hits'][0];
			return $chapterData['fields']['chapterId'][0];
		}catch(Exception $e){
			//var_dump($e->getMessage());
		}
	}
	function zipFile($bookId,$files,$params)
	{
		$fileName = self::TMP_PATH.$params['bookName'].'_*_'.$params['title'].'.html';
		$cmd = 'zip '.self::TMP_PATH.$bookId.'.zip '.$fileName;
		exec($cmd);
		error_log($cmd);
		echo 'done: '.self::TMP_PATH.$bookId.'.zip';
	}
	function saveToFile($index,$params)
	{
		$fileName = self::TMP_PATH.$params['bookName'].'_'.$index.'_'.$params['title'].'.html';
		$fp = fopen( $fileName, 'w');
		fwrite($fp, $params['text']);
		fclose($fp);
		return $fileName;
	}
	protected function loadRemoteBook($bookId,$siteName)
	{/*{{{*/
		$client = new \Elasticsearch\Client();
		$searchParams = [];
		$searchParams['index'] = 'info';
		$searchParams['type']  = 'book';
		$searchParams['body']['query']['match']['_id'] = $bookId;
		$retDoc = $client->search($searchParams);
		$books = $retDoc['hits']['hits'];
		if(empty($books)){
			$bookController = new \Api\Remote\Book;
			$params = [
				'siteName' => $siteName,
				'bookId' => $bookId
			];
			$book = $bookController->get(null,$params);
			$params = [
				'id' => "$bookId",
				'index' => 'info',
				'type' => 'book',
				'body' => $book
			];
			$ret = $client->index($params);
		}
	}/*}}}*/
	protected function loadRemoteChapters($bookId,$siteName)
	{/*{{{*/
		$chapterController = new \Api\Remote\Chapter;
		$params = [
			'siteName' => $siteName,
			'bookId' => $bookId
		];
		$chapters = $chapterController->index(null,$params);
		return $chapters;
	}/*}}}*/
	protected function loadRemoteChapter($chapterId,$bookId,$siteName)
	{/*{{{*/
		$chapterController = new \Api\Remote\Chapter;
		$params = [
			'siteName' => $siteName,
			'bookId' => $bookId,
			'chapterId' => $chapterId
		];
		$chapters = $chapterController->index(null,$params);
		return $chapters;
	}/*}}}*/
}
