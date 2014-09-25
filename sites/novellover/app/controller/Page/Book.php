<?php
namespace Page;
class Book extends \Page
{
	const ITEMS_PER_PAGE = 100;
	protected $requireDb = true;
	const TMP_PATH = '/tmp/';
	function display($f3,$param)
	{
		if(isset($_GET['dump'])){
			$this->dump($param['bookId']);
			exit;
		}
		if(isset($_GET['pg'])){
			$pg = intval($_GET['pg']);
		}else{
			$pg = 1;
		}
		$client = new \Elasticsearch\Client();
		$searchParams = [];
		$searchParams['index'] = 'info';
		$searchParams['type']  = 'book';
		$searchParams['id'] = $param['bookId'];
		$retDoc = $client->get($searchParams);
		$this->f3->set('book',$retDoc['_source']);
		$this->f3->set('bookId',$param['bookId']);

		$searchParams = [];
		$searchParams['index'] = 'chapter';
		$searchParams['type']  = 'book';
		$searchParams['fields']  = ['title','chapterId'];
		$searchParams['body']['query']['match']['bookId'] = $param['bookId'];
		$searchParams['sort'] = ['chapterId'];
		$searchParams['size'] = self::ITEMS_PER_PAGE;
		$searchParams['from'] = ($pg-1)*self::ITEMS_PER_PAGE;
		$retDoc = $client->search($searchParams);
		$chapterData = $retDoc['hits'];

		$prevPg = false;
		$nextPg = false;
		if($pg>1){
			$prevPg = $pg-1;
		}
		if($chapterData['total']>$pg*self::ITEMS_PER_PAGE){
			$nextPg = $pg+1;
		}
		$this->f3->set('chapters',$chapterData);
		$this->f3->set('pg',$pg);
		$this->f3->set('prevPg',$prevPg);
		$this->f3->set('nextPg',$nextPg);
	}
	function dump($bookId)
	{
		$client = new \Elasticsearch\Client();
		$searchParams = [];
		$searchParams['index'] = 'chapter';
		$searchParams['type']  = 'book';
		$searchParams['body']['query']['match']['bookId'] = $bookId;
		$searchParams['sort'] = ['chapterId'];
		$searchParams['size'] = 10000;
		$retDoc = $client->search($searchParams);
		$chapterData = $retDoc['hits']['hits'];
		$index = 0;
		foreach($chapterData as $chapter){
			$index++;
			$this->saveChapterToFile($index,$chapter);
		}
		var_dump('finished',$index);
	}
	function saveChapterToFile($index,$chapter)
	{
		$fileName = self::TMP_PATH.$chapter['_source']['bookName'].'_'.$index.'.html';
		$fp = fopen( $fileName, 'w');
		fwrite($fp, $chapter['_source']['text']);
		fclose($fp);
		return $fileName;
	}
}
