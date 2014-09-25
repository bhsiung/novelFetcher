<?php

abstract class Api
{
	protected $response;
	protected $f3;
	protected $params;
	function afterRoute($f3,$params)
	{
		header('Content-Type: application/json');
		echo json_encode($this->response);
	}
	function beforeRoute($f3,$params)
	{
		$this->f3 = $f3;
		$this->params = $params;
		$this->response = [];
	}
	function index($f3,$params)
	{
		throw new Exception('index is not avaialbe');
	}
	function get($f3,$params)
	{
		throw new Exception('get is not avaialbe');
	}
	function put($f3,$params)
	{
		throw new Exception('put is not avaialbe');
	}
	function post($f3,$params)
	{
		throw new Exception('post is not avaialbe');
	}
	// for script
	function setOptions($options)
	{
		$_GET['options'] = $options;
	}
	function options($key=null)
	{
		if(isset($_GET['options'])){
			$options = json_decode($_GET['options']);
			if($options){
				if(!isset($key)){
					return $options;
				} else if($key && isset($options->$key)){
					return $options->$key;
				}
			}
		}
	}
}
