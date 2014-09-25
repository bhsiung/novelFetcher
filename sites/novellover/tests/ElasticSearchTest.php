<?php
require_once('/var/www/lib/vendor/autoload.php');

class ElasticSearchTest extends \PHPUnit_Framework_TestCase
{
	function testIndex()
	{
		$client = new Elasticsearch\Client();
		$params = array();
		$params['body']  = array('testField' => 'abc');
		$params['index'] = 'my_index';
		$params['type']  = 'my_type';
		$params['id']    = 'my_id';
		$ret = $client->index($params);
		var_dump($ret);
	}
}
?>
