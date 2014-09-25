<?php
namespace Page;
class TestPage extends \Page{
	function ajax()
	{
		echo json_encode(array('name'=>'awesome'));
	}
	static function login()
	{
		echo "here we go login";
	}
	function godb()
	{
		echo '<h1>direct sql (all items)</h1>';
		$db = new \DB\SQL(
			'mysql:host=localhost;port=3306;dbname=books',
			'root',
			'29142029'
		);
		$result = $db->exec('SELECT * FROM books');
		var_dump($result);

		echo '<h1>orm</h1>';
		$book=new \DB\SQL\Mapper($db,'books');
		$book->load(array('id=?',2));
		var_dump($book->name);

		echo '<h1>cursor</h1>';
		$cursor=new \DB\SQL\Mapper($db,'books');
		$cursor->load(array('id>?',0));
		$books = $cursor->paginate(0,6,array('id>?',0),array('order'=>'id desc')); //param skip page, item per page, where clause
		foreach($books['subset'] as $book){
			var_dump($book->name);
		}
	}
}
