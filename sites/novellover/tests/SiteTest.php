<?php
namespace TestNamespace;

class SiteTest extends \PHPUnit_Framework_TestCase
{
	function testAsyncCraw()
	{
		$siteName = 'kxwxw';
		$bookId = 91875;
		$chapters = json_decode('{"4635634":{"title":"\u7b2c\u4e00\u7ae0 \u5c0f\u96de\u96de\u6709\u6059\u5426","url":"http:\/\/tw.kxwxw.com\/read\/zqqs_91875_4635634.html","id":"4635634"},"4635635":{"title":"\u7b2c\u4e8c\u7ae0 \u5be7\u5bb6\u8981\u9000\u89aa","url":"http:\/\/tw.kxwxw.com\/read\/zqqs_91875_4635635.html","id":"4635635"}}');
		\Site::asyncCraw($siteName,$bookId,$chapters);
		$this->assertTrue(true,'should be good');
	}
}
?>
