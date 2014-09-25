<?php
namespace Page;
class NewBook extends \Page
{
	function get($f3,$params)
	{
		$this->isCrawing = false;
		$this->errors = [];
		$f3->set('errors',$this->errors);
		$f3->set('isCrawing',$this->isCrawing);
		$f3->set('remoteURL',$this->prepareRemoteURL());
	}
	function prepareRemoteURL()
	{
		//http://tw.kxwxw.com/info/jxzs_96250.html
		if(isset($_GET['bookId'])){
			return 'http://tw.kxwxw.com/info/test_'.$_GET['bookId'].'.html';
		}
	}
	function post($f3,$params)
	{
		$this->isCrawing = false;
		$this->errors = [];
		try{
			$newUrl = @$f3->get('POST')['new-url'];
			if(empty($newUrl)){
				throw new \Exception('nothing expected got posted');
			}
			$className = \Site::classNameByUrl($newUrl);
			if(empty($className)){
				throw new \Exception('invalid URL given');
			}
			$bookId = call_user_func("$className::bookIdFromUrl",$newUrl);
			\Site::asyncCraw($className::NAME,$bookId);
			$this->isCrawing = true;
		}catch(\Exception $e){
			$this->errors[] = $e->getMessage();
		}
		$f3->set('errors',$this->errors);
		$f3->set('isCrawing',$this->isCrawing);
	}
}
