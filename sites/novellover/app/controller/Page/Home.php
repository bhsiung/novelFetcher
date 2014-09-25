<?php
namespace Page;
class Home extends \Page
{
	protected $requireDb = true;
	function display($f3,$params)
	{
		/*
		$cursor = new \Book($this->db);
		$books = $cursor->paginate(0,6,array('id>?',0),array('order'=>'id desc')); //param skip page, item per page, where clause
		$this->f3->set('books',$books['subset']);
		*/
		$client = new \Elasticsearch\Client();
		$searchParams = [];
		$searchParams['index'] = 'info';
		$searchParams['type']  = 'book';
		$retDoc = $client->search($searchParams);
		$books = $retDoc['hits']['hits'];
		foreach($books as &$book){
			$book['lastChapter'] = $this->lastChapter($book['_id']);
		}
		$this->f3->set('books',$books);
	}
	function lastChapter($bookId)
	{
		try{
			$client = new \Elasticsearch\Client();
			$searchParams = [];
			$searchParams['index'] = 'chapter';
			$searchParams['type']  = 'book';
			$searchParams['fields']  = ['title','chapterId'];
			$searchParams['body']['query']['match']['bookId'] = $bookId;
			$searchParams['body']['sort'] = ['chapterId'=>['order'=>'desc']];
			$searchParams['size'] = 1;
			$retDoc = $client->search($searchParams);
			if(!isset($retDoc['hits']['hits'][0])){
				return;
			}
			$chapterData = $retDoc['hits']['hits'][0];
			return $chapterData;
		}catch(Exception $e){
			var_dump($e->getMessage());
		}
	}
}
