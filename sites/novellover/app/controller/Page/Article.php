<?php
namespace Page;
class Article extends \Page
{
	const DIRECTION_FORWARD = 1;
	const DIRECTION_BACKWARD = 2;
	protected $requireDb = true;
	protected $client;
	function display($f3,$params)
	{
		$this->client = new \Elasticsearch\Client();
		$searchParams = [];
		$searchParams['index'] = 'chapter';
		$searchParams['type']  = 'book';
		$searchParams['body']['query']['match']['chapterId'] = $params['articleId'];
		$retDoc = $this->client->search($searchParams);
		$articleData = $retDoc['hits']['hits'][0]['_source'];
		$prevChapterData = $this->getSiblingChapter(self::DIRECTION_BACKWARD,$articleData);
		$nextChapterData = $this->getSiblingChapter(self::DIRECTION_FORWARD,$articleData);
		$this->f3->set('article',$articleData);
		$this->f3->set('next',$nextChapterData);
		$this->f3->set('prev',$prevChapterData);
	}
	function getSiblingChapter($direction,$articleData)
	{
		$searchParams = [];
		$searchParams['index'] = 'chapter';
		$searchParams['type']  = 'book';
		$searchParams['fields']  = ['title','chapterId'];
		$searchParams['body']['query']['filtered'] = array(
			'query' => [
				'match' => ['bookId' => $articleData['bookId']]
			]
		);
		if($direction == self::DIRECTION_FORWARD){
			$searchParams['body']['query']['filtered']['filter'] = [
				'numeric_range' => ['chapterId' => ['gt' => $articleData['chapterId']]]
			];
			$searchParams['sort'] = ['chapterId'];
		}else{
			$searchParams['body']['query']['filtered']['filter'] = [
				'numeric_range' => ['chapterId' => ['lt' => $articleData['chapterId']]]
			];
			$searchParams['sort'] = [['chapterId'=>'desc']];
			
		}
		$searchParams['size'] = 1;
		$retDoc = $this->client->search($searchParams);
		$chapterData = $retDoc['hits'];
		return @$chapterData['hits'][0]['fields'];
		/*

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
		*/
	}
}
