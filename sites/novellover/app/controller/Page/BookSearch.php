<?php
namespace Page;
require_once('/var/www/lib/vendor/autoload.php');
class BookSearch extends \Page
{
	protected $requireDb = true;
	function display($f3,$param)
	{
		$client = new \Elasticsearch\Client();
		$searchParams['index'] = 'chapter';
		$searchParams['type']  = 'book';
		$searchParams['size']  = 200;
		//$searchParams['body']['query']['match']['text'] = $_GET['keyword'];
		$searchParams['body']['query']['filtered']['query']['match']['text'] = $_GET['keyword'];
		//$searchParams['sort'] = ['chapterId'];
		$retDoc = $client->search($searchParams);

		$this->f3->set('keyword',$_GET['keyword']);
		$this->f3->set('searchResult',$retDoc['hits']);
		$this->f3->set('defaultTitle','Search result of "'.$_GET['keyword'].'"');
		//var_dump($retDoc);

		/*
		$book = new \Book($this->db);
		if(!$book->load(array('id=?',$param['bookId']))){
			 $f3->error(404);
		}
		$author = new \Author($this->db);
		if(!$author->load(array('id=?',$book->authorId))){
			 $f3->error(404);
		}
		$cursor = new \DB\SQL\Mapper($this->db,'bookChapters');
		if(!$cursor->load(array('bookId=?',$book->id))){
			 $f3->error(404);
		}
		$chapters = $cursor->paginate(0,600); //param skip page, item per page, where clause
		$this->f3->set('book',$book);
		$this->f3->set('chapters',$chapters['subset']);
		$this->f3->set('authorName',$author->name);
		*/
	}
}
