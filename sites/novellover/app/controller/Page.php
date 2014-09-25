<?php
abstract class Page
{
	protected $f3;
	protected $db;
	protected $requireDb = false;
	function display($f3,$param)
	{}
	function renderTpl()
	{
		$path = get_class($this);
		$path = preg_replace('/^Page\\\/','',get_class($this));
		$path = preg_replace('/\\\/','/',$path);
		$fileName = strtolower($path);
		$path = 'app/view/'.$path.'.html';
		if(file_exists($path)){
			$this->f3->set('fileName',$fileName);
			$tpl = new Template;
			echo $tpl->render($path);
		}
	}
	function beforeRoute($f3,$params)
	{
		$this->f3 = $f3;
		if($this->requireDb){
			$this->prepareDb();
		}
	}
	function afterRoute($f3,$params)
	{
		$this->f3->scrub($_GET,'');
		$this->renderTpl();
	}
	function ajax()
	{
		echo json_encode(array('name'=>'awesome'));
	}
	function prepareDb()
	{
		if(!isset($this->db)){
			$this->db = new \DB\SQL(
				'mysql:host=localhost;port=3306;dbname=books',
				'root',
				'29142029'
			);
		}
	}
}
